<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use Illuminate\Http\Request;
use App\Models\LockersLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\LockerLog;
use Validator;
use App\Models\User;
use DateTime;



class LockerController extends Controller
{
    public function getAllLockers()
    {
        $lockers = Locker::with('user:id,first_name,last_name,program_id,gender')
            ->select('Id', 'user_id', 'locker_number', 'status')
            ->get();

        return response()->json($lockers);
    }


    public function getLockerInfo($lockerId)
    {
        // Fetch the locker with nested user, program, and department relationships
        $locker = Locker::with(['user' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'program_id', 'gender')
                  ->with(['program' => function ($programQuery) {
                      $programQuery->select('id', 'program', 'full_program', 'department_id')
                                  ->with(['department' => function ($departmentQuery) {
                                      $departmentQuery->select('id', 'department', 'full_department');
                                  }]);
                  }]);
        }])->find($lockerId);

        // Check if locker is found
        if (!$locker) {
            return response()->json(['error' => 'Locker not found'], 404);
        }

        // Return the locker info in JSON format
        return response()->json($locker);
    }


    public function index()
    {
        $lockers = Locker::select('id', 'status', 'locker_number', 'updated_at')->get();

        return $lockers;
    }

    public function store(Request $request) {
        $data = Validator::make($request->all(), [
            'numberOfLockers' => 'required|numeric|gt:0'
        ]);

        if($data->fails()){
            return response()->json(['errors' => $data->errors()], 400 );
        }

        $latestLocker = Locker::latest('id')->first();

        if(!$latestLocker) {
            $latestLockerNumber = 0;
        }
        else {
            $latestLockerNumber = intval($latestLocker->locker_number);
        }

        $lockers = [];
        
        for ($i = $latestLockerNumber + 1; $i <= $latestLockerNumber + $request->numberOfLockers; $i++) {
            $lockerNumber = str_pad($i, 3, '0', STR_PAD_LEFT);

            $locker = new Locker();
            $locker->locker_number = $lockerNumber;
            $locker->status = 'Available';
            $locker->save();

            $lockers[] = $locker;
        }
        $user_id = $request->user()->id;

        $log = new LockerHistoryController();
        if($request->numberOfLockers > 1) {
            $details = "create " . $request->numberOfLockers . " lockers";
        } 
        else {
            $details = "create " . $request->numberOfLockers . " locker";
        }

        $log->add($user_id, "create", $details);

        return response()->json(['success' => $lockers]);
    }

    public function getStartingLockerNumber() {
        $latestLocker = Locker::latest('id')->first();

        if(!$latestLocker) {
            return 1;
        }

        $latestLockerNumber = intval($latestLocker->locker_number);

        return $latestLockerNumber + 1;
    }

    public function show($id) {
        $locker = Locker::select('id', 'locker_number', 'status')->findorfail($id);
        // , 'remarks'
        return $locker;
    }

    public function update(Request $request, $id) {
        $data = Validator::make($request->all(), [
            'status' => 'required|in:Occupied,Available,Unavailable',
            // 'remarks' => 'nullable|string|max:256'
        ]);

        if($data->fails()){
            return response()->json(['errors' => $data->errors()], 400 );
        }

        $locker = Locker::findorfail($id);
        $locker->update($data->validated());

        $user_id = $request->user()->id;

        $log = new LockerHistoryController();
        $log->add($user_id, "update", "update locker #" . $locker->locker_number);

        return response()->json(['success' => $locker]);
    }

    public function destroy($id) {
        $latestLocker = Locker::latest('id')->first();

        // return $latestLocker->id;

        if($latestLocker->id != $id) {
            return response()->json(['errors' => 'You must delete the latest locker first.'], 400 );
        }

        $locker = Locker::findorfail($id);
        $locker->delete();

        $user_id = Auth()->user()->id;

        $log = new LockerHistoryController();
        $log->add($user_id, "delete", "delete locker #" . $locker->lockerNumber);
        
        return response()->json(['success' => 'Locker has been deleted.']);
    }

//LOCKER MAINTENANCE
public function locker(Request $request)
{
    $request->validate([
        'locker_number' => 'required|unique:lockers',
        'status' => 'required',
    ]);

    $locker = new Locker();
    $locker->locker_number = $request->input('locker_number');
    $locker->status = $request->input('status');
    $locker->save();

    return response()->json(['message' => 'Locker added successfully'], 201);
}



public function scanLockerQRCode(Request $request, $lockerId)
{
    try {
        $scannedData = $request->input('scannedData');
        $userId = null;

        if ($scannedData && strpos($scannedData, 'StudentNumber:') === 0) {
            $parts = explode(':', $scannedData);
            $userId = $parts[1];
        } else {
            $locker = Locker::find($lockerId);

            if (!$locker) {
                Log::error('Locker not found: ' . $lockerId);
                return response()->json(['error' => 'Locker not found'], 404);
            }

            if ($locker->status === 'Occupied') {
                $locker->status = 'Available';
                $locker->user_id = null;

                $log = LockersLog::where('locker_id', $locker->id)->whereNull('time_out')->first();
                if ($log) {
                    $log->update(['time_out' => Carbon::now()]);
                }

                $locker->save();
                Log::debug('Updated locker: ' . json_encode($locker));
                return response()->json($locker);
            } else {
                Log::error('Locker not occupied: ' . $lockerId);
                return response()->json(['error' => 'Locker not occupied'], 400);
            }
        }

        Log::debug('Scanned data: ' . $scannedData);
        Log::debug('User ID: ' . $userId);

        $user = User::find($userId);

        if (!$user) {
            Log::error('User not found with ID: ' . $userId);
            return response()->json(['error' => 'User not found'], 404);
        }

        $locker = Locker::find($lockerId);

        if (!$locker) {
            Log::error('Locker not found: ' . $lockerId);
            return response()->json(['error' => 'Locker not found'], 404);
        }

        Log::debug('Locker: ' . json_encode($locker));

        if ($locker->status === 'Occupied') {
            if ($user->id !== $locker->user_id) {
                Log::error('Invalid user ID: ' . $userId . ' for locker: ' . $lockerId);
                return response()->json(['error' => 'User ID doesn\'t match'], 400);
            }
        }

        if ($locker->status === 'Occupied') {
            $locker->status = 'Available';
            $locker->user_id = null;

            $log = LockersLog::where('user_id', $user->id)->whereNull('time_out')->first();
            if ($log) {
                $log->update(['time_out' => Carbon::now()]);
            }
        } else {
            $locker->status = 'Occupied';
            $locker->user_id = $user->id;

            LockersLog::create([
                'locker_id' => $locker->id,
                'user_id' => $user->id,
                'time_in' => Carbon::now(),
            ]);
        }

        $locker->save();
        Log::debug('Updated locker: ' . json_encode($locker));
        return response()->json($locker);
    } catch (\Exception $e) {
        Log::error('Error scanning QR code: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}


public function getLockerCounts()
{
    $available = Locker::where('status', 'available')->count();
    $occupied = Locker::where('status', 'occupied')->count();
    $unavailable = Locker::where('status', 'unavailable')->count();
    $total = Locker::count();

    // Assuming may relasyon ang Locker model sa user logs, kunin ang bilang ng distinct users na may kaugnayan sa locker logs
    $totalUsers = LockersLog::distinct()->count('id');

    // Add filtering by days, weeks, and months for total users
    $todayUsers = LockersLog::whereDate('created_at', today())->distinct()->count('id');
    $thisWeekUsers = LockersLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->distinct()->count('id');
    $thisMonthUsers = LockersLog::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->distinct()->count('id');

    $counts = [
        'available' => $available,
        'occupied' => $occupied,
        'unavailable' => $unavailable,
        'total' => $total,
        'totalUsers' => $totalUsers,
        'todayUsers' => $todayUsers,
        'thisWeekUsers' => $thisWeekUsers,
        'thisMonthUsers' => $thisMonthUsers,
    ];

    return response()->json($counts);
}

// Other methods as needed

public function getLockersWithUsers()
{
    $lockersWithUsers = Locker::with('user')->get();

    return response()->json($lockersWithUsers);
}


public function getGenderCounts(Request $request)
{
    $period = $request->query('period');

    $query = LockersLog::selectRaw('count(*) as count, users.gender')
        ->join('users', 'users.id', '=', 'lockers_logs.user_id')
        ->groupBy('users.gender');

    $startDate = null;
    $endDate = null;

    // Get results without any date filtering first
    $results = $query->get();

    switch ($period) {
        case 'today':
            $startDate = new DateTime();
            $startDate->setTime(0, 0, 0);
            $endDate = new DateTime();
            $endDate->setTime(23, 59, 59);
            break;
        case 'week':
            $startDate = new DateTime('monday this week'); // Start of current week
            $endDate = new DateTime('sunday this week'); // End of current week
            $endDate->setTime(23, 59, 59);
            break;
        case 'month':
            $startDate = new DateTime();
            $startDate->modify('first day of this month');
            $endDate = new DateTime();
            $endDate->modify('last day of this month');
            $endDate->setTime(23, 59, 59);
            break;
        case 'all':
            // No need to set start and end date for 'all'
            break;
        default:
            // Handle invalid or missing period parameter
            return response()->json(['error' => 'Invalid period parameter.']);
    }

    // Apply date filtering if start and end dates are set
    if ($startDate && $endDate) {
        $query->whereBetween('lockers_logs.created_at', [$startDate, $endDate]);
    }

    // Fetch results with applied date filtering
    $results = $query->get();

    $maleCount = 0;
    $femaleCount = 0;

    foreach ($results as $result) {
        // Check if gender is 1 (male) or 0 (female)
        if ($result->gender == 1) {
            $maleCount += $result->count;
        } elseif ($result->gender == 0) {
            $femaleCount += $result->count;
        }
    }

    $genderCounts = [
        'maleCount' => $maleCount,
        'femaleCount' => $femaleCount,
    ];

    return response()->json($genderCounts);
}


public function getCollegeCounts(Request $request)
{

    $filter = $request->input('filter'); // get the filter type (days, weeks, months)
    $dateRange = null;

    switch ($filter) {
        case 'days':
            $dateRange = [now()->startOfDay(), now()->endOfDay()];
            break;
        case 'weeks':
            $dateRange = [now()->startOfWeek(), now()->endOfWeek()];
            break;
        case 'months':
            $dateRange = [now()->startOfMonth(), now()->endOfMonth()];
            break;
        default:
            // no filter, return all data
            $dateRange = null;
    }

    // Department counts

    $ceasCount = LockersLog::whereHas('user.department', function ($query) use ($dateRange) {
        $query->where('department', 'CEAS');
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }
    })->count();

    $chtmCount = LockersLog::whereHas('user.department', function ($query) use ($dateRange) {
        $query->where('department', 'CHTM');
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }
    })->count();


    $cbaCount = LockersLog::whereHas('user.department', function ($query) use ($dateRange) {
        $query->where('department', 'CBA');
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }
    })->count();

    $cahsCount = LockersLog::whereHas('user.department', function ($query) use ($dateRange) {
        $query->where('department', 'CAHS');
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }
    })->count();

    $ccsCount = LockersLog::whereHas('user.department', function ($query) use ($dateRange) {
        $query->where('department', 'CCS');
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }
    })->count();



    // Program counts for CEAS department
    $ceasProgramCounts = [
        'BACOMM' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BACOMM');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BCAED' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BCAED');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BECED' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BECED');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BEED' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BEED');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BPED' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BPED');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDBIO' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSEDBIO');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDENG' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSEDENG');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDFIL' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSEDFIL');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDMATH' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSEDMATH');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDMAPEH' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSEDMAPEH');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDSCI' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSEDSCI');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDSOC' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSEDSOC');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSEDPROFED' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSEDPROFED');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),
    ];


    // Program counts for CHTM department
    $chtmProgramCounts = [
        // 'BSHM' =>LockersLog::where('collegeDepartment', 'CHTM')->where('collegeProgram', 'BSHM')->count(),
        'BSHM' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSHM');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),

        // 'BSHRM' =>LockersLog::where('collegeDepartment', 'CHTM')->where('collegeProgram', 'BSHRM')->count(),
        'BSHRM' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSHRM');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),

        // 'BSTM' =>LockersLog::where('collegeDepartment', 'CHTM')->where('collegeProgram', 'BSTM')->count(),
        'BSTM' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSTM');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),
    ];



    // Program counts for CBA department
    $cbaProgramCounts = [
        'BSA' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSA');
            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSBAFM' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSBA-FM');

            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSBAHRM' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSBAHRM');

            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSBAMKT' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSBAMKT');

            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),

        'BSCA' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSCA');

            if ($dateRange) {
                $query->whereBetween('lockers_logs.created_at', $dateRange);
            }
        })->count(),
    ];


    // Program counts for CAHS department
    $cahsProgramCounts = [
        'BSM' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSM');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),

        'BSN' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSN');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),
    ];



    // Program counts for CCS department
    $ccsProgramCounts = [
        // 'BSCS' =>LockersLog::where('collegeDepartment', 'CCS')->where('collegeProgram', 'BSCS')->count(),
        'BSCS' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSCS');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),

        'BSIT' => LockersLog::whereHas('user.program', function ($query) use ($dateRange) {
            $query->where('program', 'BSIT');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),

        // 'BSEMC' =>LockersLog::where('collegeDepartment', 'CCS')->where('collegeProgram', 'BSEMC')->count(),
        'BSEMC' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'BSEMC');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),


        // 'ACT' => LockersLog::where('collegeDepartment', 'CCS')->where('collegeProgram', 'ACT')->count(),
        'ACT' => LockersLog::whereHas('user.program', function ($query)  use ($dateRange) {
            $query->where('program', 'ACT');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count(),

    ];


    // Prepare the response array
    $collegeCounts = [
        'CEAS' => [
            'departmentCount' => $ceasCount,
            'programCounts' => $ceasProgramCounts,
        ],
        'CHTM' => [
            'departmentCount' => $chtmCount,
            'programCounts' => $chtmProgramCounts,
        ],
        'CBA' => [
            'departmentCount' => $cbaCount,
            'programCounts' => $cbaProgramCounts,
        ],
        'CAHS' => [
            'departmentCount' => $cahsCount,
            'programCounts' => $cahsProgramCounts,
        ],
        'CCS' => [
            'departmentCount' => $ccsCount,
            'programCounts' => $ccsProgramCounts,
        ],
    ];

    // Return the response as JSON
    return response()->json($collegeCounts);
}
}
