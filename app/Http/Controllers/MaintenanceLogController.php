<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceLogController extends Controller
{
    /**
     * Log a maintenance activity.
     *
     * @param  string  $activity
     * @param  string  $description
     * @return \Illuminate\Http\Response
     */
    public function logMaintenanceActivity($activity, $description)
    {
        Log::info("Maintenance Activity: $activity - $description");

        return response()->json(['message' => 'Maintenance activity logged successfully'], 200);
    }

    /**
     * Get all maintenance log entries.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllMaintenanceLogs()
    {
        $logs = Log::get('maintenance');

        return response()->json(['logs' => $logs], 200);
    }

    /**
     * Get maintenance log entries by activity type.
     *
     * @param  string  $activity
     * @return \Illuminate\Http\Response
     */
    public function getMaintenanceLogsByActivity($activity)
    {
        $logs = Log::where('channel', 'maintenance')->where('message', 'LIKE', "%$activity%")->get();

        return response()->json(['logs' => $logs], 200);
    }

    /**
     * Clear all maintenance log entries.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearMaintenanceLogs()
    {
        Log::channel('maintenance')->truncate();

        return response()->json(['message' => 'All maintenance logs cleared successfully'], 200);
    }
}
