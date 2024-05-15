<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LockersLog;
use Illuminate\Http\JsonResponse;

class LockersLogController extends Controller
{
    /**
     * Display a listing of the lockers logs.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $lockersLogs = LockersLog::all();
        return response()->json(['lockersLogs' => $lockersLogs]);
    }

    /**
     * Store a newly created lockers log in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'lockerID' => 'required',
            'status' => 'required',
            'date_time' => 'required',
        ]);

        $lockersLog = LockersLog::create($request->all());

        return response()->json(['message' => 'Lockers log created successfully', 'lockersLog' => $lockersLog], 201);
    }

    /**
     * Display the specified lockers log.
     *
     * @param LockersLog $lockersLog
     * @return JsonResponse
     */
    public function show(LockersLog $lockersLog): JsonResponse
    {
        return response()->json(['lockersLog' => $lockersLog]);
    }

    /**
     * Update the specified lockers log in storage.
     *
     * @param Request $request
     * @param LockersLog $lockersLog
     * @return JsonResponse
     */
    public function update(Request $request, LockersLog $lockersLog): JsonResponse
    {
        $request->validate([
            'lockerID' => 'required',
            'status' => 'required',
            'date_time' => 'required',
        ]);

        $lockersLog->update($request->all());

        return response()->json(['message' => 'Lockers log updated successfully', 'lockersLog' => $lockersLog]);
    }

    /**
     * Remove the specified lockers log from storage.
     *
     * @param LockersLog $lockersLog
     * @return JsonResponse
     */
    public function destroy(LockersLog $lockersLog): JsonResponse
    {
        $lockersLog->delete();

        return response()->json(['message' => 'Lockers log deleted successfully']);
    }
}
