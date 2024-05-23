<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use Illuminate\Http\Request;
use App\Models\LockersLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\LockerLog;
use Illuminate\Support\Facades\Validator;
use App\Models\User;



class LockerController extends Controller
{
    public function getAllLockers()
    {
        $lockers = Locker::with('user:id,first_name,last_name,studentNumber,collegeProgram,collegeDepartment,gender')
            ->select('Id', 'user_id', 'lockerNumber', 'status')
            ->get();

        return response()->json($lockers);
    }


    public function getLockerInfo($lockerId)
    {
        $locker = Locker::with(['user' => function($query) {
            $query->select('id', 'first_name', 'last_name', 'studentNumber', 'collegeProgram', 'collegeDepartment','gender');
        }])->findOrFail($lockerId);

        if (!$locker) {
            return response()->json(['error' => 'Locker not found'], 404);
        }

        return response()->json($locker);
    }



    public function index()
    {
        $lockers = Locker::select('id', 'status', 'lockerNumber', 'updated_at')->get();

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
            $latestLockerNumber = intval($latestLocker->lockerNumber);
        }

        
        for ($i = $latestLockerNumber + 1; $i <= $latestLockerNumber + $request->numberOfLockers; $i++) {
            $lockerNumber = str_pad($i, 3, '0', STR_PAD_LEFT);

            $locker = new Locker();
            $locker->lockerNumber = $lockerNumber;
            $locker->save();
        }

        return response()->json(['success' => 'Locker has been created']);
    }

    public function getStartingLockerNumber() {
        $latestLocker = Locker::latest('id')->first();

        if(!$latestLocker) {
            return 1;
        }

        $latestLockerNumber = intval($latestLocker->lockerNumber);

        return $latestLockerNumber + 1;
    }

    public function show($id) {
        $locker = Locker::select('id', 'lockerNumber', 'status', 'remarks')->findorfail($id);

        return $locker;
    }

    public function update(Request $request, $id) {
        $data = Validator::make($request->all(), [
            'status' => 'required|in:Occupied,Available,Unavailable',
            'remarks' => 'nullable|string|max:256'
        ]);

        if($data->fails()){
            return response()->json(['errors' => $data->errors()], 400 );
        }

        Locker::findorfail($id)->update($data->validated());

        return response()->json(['success' => 'Locker has been updated']);
    }

    public function destroy($id) {
        $latestLocker = Locker::latest('id')->first();

        // return $latestLocker->id;

        if($latestLocker->id != $id) {
            return response()->json(['errors' => 'You must delete the latest locker first.'], 400 );
        }


        $locker = Locker::findorfail($id)->delete();
        
        return response()->json(['success' => 'Locker has been deleted.']);
    }









//LOCKER MAINTENANCE
    public function locker(Request $request)
    {
        $request->validate([
            'lockerNumber' => 'required|unique:lockers',
            'status' => 'required',
        ]);

        $locker = new Locker();
        $locker->lockerNumber = $request->input('lockerNumber');
        $locker->status = $request->input('status');
        $locker->save();

        return response()->json(['message' => 'Locker added successfully'], 201);
    }















    public function scanLockerQRCode(Request $request, $lockerId)
    {
        try {
            $scannedData = $request->input('scannedData');
            $studentNumber = null;

            // Validate scanned data format
            if ($scannedData && strpos($scannedData, 'StudentNumber:') === 0) {
                $parts = explode(':', $scannedData);
                $studentNumber = $parts[1];

            } else {
                // Manual logout logic if scannedData is not provided or invalid
                $locker = Locker::find($lockerId);

                if (!$locker) {
                    Log::error('Locker not found: ' . $lockerId);
                    return response()->json(['error' => 'Locker not found'], 404);
                }

                // Update locker information for manual logout
                if ($locker->status === 'Occupied') {
                    // Change status to Available and clear student number when locker becomes Available
                    $locker->status = 'Available';
                    $locker->studentNumber = null;
                    $locker->user_id = null;

                    // Update lockers log with time_out
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
            Log::debug('Student number: ' . $studentNumber);

            // Find the user by student number
            $user = User::where('studentNumber', '=', trim($studentNumber))->first();

            if (!$user) {
                Log::error('User not found with student number: ' . $studentNumber);
                return response()->json(['error' => 'User not found'], 404);
            }


            // Find the locker by ID
            $locker = Locker::find($lockerId);

            if (!$locker) {
                Log::error('Locker not found: ' . $lockerId);
                return response()->json(['error' => 'Locker not found'], 404);
            }

            Log::debug('Locker: ' . json_encode($locker));

            // Validate student number against locker student number if locker is occupied
            if ($locker->status === 'Occupied') {
                if ($user->id !== $locker->user_id) {
                    Log::error('Invalid student number: ' . $studentNumber . ' for locker: ' . $lockerId);
                    return response()->json(['error' => 'Student number doesn\'t match'], 400);
                }
            }

            // Update locker information based on status
            if ($locker->status === 'Occupied') {
                // Change status to Available and clear student number when locker becomes Available
                $locker->status = 'Available';
                $locker->studentNumber = null;
                $locker->user_id = null;



                // Update lockers log with time_out
                $log = LockersLog::where('user_id', $user->id)->whereNull('time_out')->first();
                if ($log) {
                    $log->update(['time_out' => Carbon::now()]);
                }
            } else {
                // Change status to Occupied and set user_id for the locker
                $locker->status = 'Occupied';
                $locker->user_id = $user->id;

                // Create new lockers log entry
                LockersLog::create([
                    'locker_id' => $locker->id,
                    'user_id' => $user->id,
                    // Add other necessary fields here
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















    // public function getLockerCounts()
    // {
    //     $available = Locker::where('status', 'available')->count();
    //     $occupied = Locker::where('status', 'occupied')->count();
    //     $unavailable = Locker::where('status', 'unavailable')->count();
    //     $total = Locker::count();

    //     // Assuming may relasyon ang Locker model sa user logs, kunin ang bilang ng distinct users na may kaugnayan sa locker logs
    //     $totalUsers = LockersLog::distinct()->count('id');

    //     $counts = [
    //         'available' => $available,
    //         'occupied' => $occupied,
    //         'unavailable' => $unavailable,
    //         'total' => $total,
    //         'totalUsers' => $totalUsers, // Ito ang bilang ng distinct users na may kaugnayan sa locker logs
    //     ];

    //     return response()->json($counts);
    // }

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

    public function getLockerHistory()
    {
        $lockerHistory = Locker::all(); // Assuming LockerHistory model exists

        return response()->json($lockerHistory);
    }











    // public function getcollegeProgramCounts()
    // {
    //     $ceasCount = Locker::where('collegeDepartment', 'CEAS')->count();
    //     $chtmCount = Locker::where('collegeDepartment', 'CHTM')->count();
    //     $cbaCount = Locker::where('collegeDepartment', 'CBA')->count();
    //     $cahsCount = Locker::where('collegeDepartment', 'CAHS')->count();
    //     $ccsCount = Locker::where('collegeDepartment', 'CCS')->count();

    //     $collegeProgramCounts = [
    //         'ceasCount' => $ceasCount,
    //         'chtmkCount' => $chtmCount,
    //         'cbaCount' => $cbaCount,
    //         'cahsCount' => $cahsCount,
    //         'ccsCount' => $ccsCount,
    //     ];

    //     return response()->json($collegeProgramCounts);
    // }


    public function getGenderCounts()
    {
        $maleCount = LockersLog::whereHas('user', function ($query) {
            $query->where('gender', 'male');
        })->count();


        $femaleCount = LockersLog::whereHas('user', function ($query) {
            $query->where('gender', 'female');
        })->count();


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
        $ceasCount = LockersLog::whereHas('user', function ($query) use ($dateRange) {
            $query->where('collegeDepartment', 'CEAS');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count();

        $chtmCount = LockersLog::whereHas('user' , function ($query) use ($dateRange) {
            $query->where('collegeDepartment', 'CHTM');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count();

        $cbaCount = LockersLog::whereHas('user' , function ($query) use ($dateRange) {
            $query->where('collegeDepartment', 'CBA');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count();

        $cahsCount = LockersLog::whereHas('user' , function ($query) use ($dateRange) {
            $query->where('collegeDepartment', 'CAHS');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count();

        $ccsCount = LockersLog::whereHas('user' , function ($query) use ($dateRange) {
            $query->where('collegeDepartment', 'CCS');
            if ($dateRange) {
                $query->whereBetween('created_at', $dateRange);
            }
        })->count();


        // Program counts for CEAS department
        $ceasProgramCounts = [
            // 'BACOMM' =>LockersLog::where('collegeProgram', 'BACOMM')->count(),

            'BACOMM' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BACOMM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BCAED' =>LockersLog::where('collegeProgram', 'BCAED')->count(),
            'BCAED' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BCAED');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BECED' =>LockersLog::where('collegeProgram', 'BECED')->count(),
            'BECED' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BECED');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BEED' =>LockersLog::where('collegeProgram', 'BEED')->count(),
            'BEED' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BEED');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BPED' =>LockersLog::where('collegeProgram', 'BPED')->count(),
            'BPED' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BPED');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSEDP-BIO' =>LockersLog::where('collegeProgram', 'BSEDP-BIO')->count(),
            'BSEDBIO' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSEDBIO');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-END' =>LockersLog::where('collegeProgram', 'BSED-END')->count(),
            'BSEDENG' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSEDENG');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-FIL' =>LockersLog::where('collegeProgram', 'BSED-FIL')->count(),
            'BSEDFIL' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSEDFIL');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-MATH' =>LockersLog::where('collegeProgram', 'BSED-MATH')->count(),
            'BSEDMATH' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSEDMATH');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-MAPED' =>LockersLog::where('collegeProgram', 'BSED-MAPED')->count(),
            'BSEDMAPEH' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSEDMAPEH');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-SCI' =>LockersLog::where('collegeProgram', 'BSED-SCI')->count(),
            'BSEDSCI' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSEDSCI');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-SOC' =>LockersLog::where('collegeProgram', 'BSED-SOC')->count(),
            'BSEDSOC' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSEDSOC');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSED-PROFED' =>LockersLog::where('collegeProgram', 'BSED-PROFED')->count(),
            'BSEDPROFED' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSEDPROFED');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

        ];

        // Program counts for CHTM department
        $chtmProgramCounts = [
            // 'BSHM' =>LockersLog::where('collegeDepartment', 'CHTM')->where('collegeProgram', 'BSHM')->count(),
            'BSHM' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSHM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSHRM' =>LockersLog::where('collegeDepartment', 'CHTM')->where('collegeProgram', 'BSHRM')->count(),
            'BSHRM' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSHRM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSTM' =>LockersLog::where('collegeDepartment', 'CHTM')->where('collegeProgram', 'BSTM')->count(),
            'BSTM' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSTM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),
        ];

        // Program counts for CBA department
        $cbaProgramCounts = [
            // 'BSA' =>LockersLog::where('collegeDepartment', 'CBA')->where('collegeProgram', 'BSA')->count(),
            'BSA' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSA');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSBA-FM' =>LockersLog::where('collegeDepartment', 'CBA')->where('collegeProgram', 'BSBA-FM')->count(),
            'BSBAFM' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSBAFM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSBA-HRM' =>LockersLog::where('collegeDepartment', 'CBA')->where('collegeProgram', 'BSBA-HRM')->count(),
            'BSBAHRM' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSBAHRM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSBA-MKT' =>LockersLog::where('collegeDepartment', 'CBA')->where('collegeProgram', 'BSBA-MKT')->count(),
            'BSBAMKT' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSBAMKT');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSCA' =>LockersLog::where('collegeDepartment', 'CBA')->where('collegeProgram', 'BSCA')->count(),
            'BSCA' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSCA');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),
        ];

        // Program counts for CAHS department
        $cahsProgramCounts = [
            // 'BSM' =>LockersLog::where('collegeDepartment', 'CAHS')->where('collegeProgram', 'BSM')->count(),
            'BSM' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSM');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSN' =>LockersLog::where('collegeDepartment', 'CAHS')->where('collegeProgram', 'BSN')->count(),
            'BSN' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSN');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),
        ];

        // Program counts for CCS department
        $ccsProgramCounts = [
            // 'BSCS' =>LockersLog::where('collegeDepartment', 'CCS')->where('collegeProgram', 'BSCS')->count(),
            'BSCS' => LockersLog::whereHas('user' , function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSCS');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            'BSIT' => LockersLog::whereHas('user', function ($query) use ($dateRange) {
                $query->where('collegeProgram', 'BSIT');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),

            // 'BSEMC' =>LockersLog::where('collegeDepartment', 'CCS')->where('collegeProgram', 'BSEMC')->count(),
            'BSEMC' => LockersLog::whereHas('user', function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'BSEMC');
                if ($dateRange) {
                    $query->whereBetween('created_at', $dateRange);
                }
            })->count(),


            // 'ACT' => LockersLog::where('collegeDepartment', 'CCS')->where('collegeProgram', 'ACT')->count(),
            'ACT' => LockersLog::whereHas('user' , function ($query)  use ($dateRange){
                $query->where('collegeProgram', 'ACT');
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
