<?php

namespace App\Http\Controllers;

use App\Models\LockersLog;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Locker; // Import the Locker model

class LockersLogController extends Controller
{

    public function getLockerLogs()
{
    // Retrieve locker logs along with locker details
    $logsWithLockers = LockersLog::with('locker')->get();

    return response()->json($logsWithLockers, 200);
}




    // public function fetchLockersLogsWithUsers()
    // {
    //     $lockersLogsWithUsers = LockersLog::with('user')->with('locker')->get();
    //     return response()->json($lockersLogsWithUsers);
    // }

    public function fetchLockersLogsWithUsers(Request $request)
    {
        $filterBy = $request->input('filter_by'); // Get the filter type from the request
        $lockersLogsWithUsers = LockersLog::with('user')->with('locker');

        switch ($filterBy) {
            case 'today':
                $lockersLogsWithUsers->whereDate('created_at', today());
                break;
            case 'week':
                $lockersLogsWithUsers->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $lockersLogsWithUsers->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
            default:
                // No filter applied, return all logs
                break;
        }

        $lockersLogsWithUsers = $lockersLogsWithUsers->get();
        return response()->json($lockersLogsWithUsers);
    }

//     public function fetchLockersLogsWithUsers(Request $request)
// {
//     $query = LockersLog::with('user')->with('locker');

//     // Check if there's a 'day' parameter in the request
//     if ($request->has('day')) {
//         $day = $request->input('day');
//         $query->whereDate('created_at', '=', $day); // Filter by specific day
//     }

//     // Check if there's a 'week' parameter in the request
//     if ($request->has('week')) {
//         $week = $request->input('week');
//         $query->whereRaw('WEEK(created_at) = ?', [$week]); // Filter by specific week
//     }

//     // Check if there's a 'month' parameter in the request
//     if ($request->has('month')) {
//         $month = $request->input('month');
//         $query->whereMonth('created_at', '=', $month); // Filter by specific month
//     }

//     // Add more conditions as needed based on your filtering requirements

//     $lockersLogsWithUsers = $query->get();

//     return response()->json($lockersLogsWithUsers);
// }



}
