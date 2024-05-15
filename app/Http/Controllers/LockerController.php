<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LockerController extends Controller
{
    /**
     * Display a listing of the lockers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $lockers = Locker::all();
        return response()->json(['lockers' => $lockers]);
    }

    /**
     * Store a newly created locker in storage.
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

        $locker = Locker::create($request->all());

        return response()->json(['message' => 'Locker created successfully', 'locker' => $locker], 201);
    }

    /**
     * Display the specified locker.
     *
     * @param Locker $locker
     * @return JsonResponse
     */
    public function show(Locker $locker): JsonResponse
    {
        return response()->json(['locker' => $locker]);
    }

    /**
     * Update the specified locker in storage.
     *
     * @param Request $request
     * @param Locker $locker
     * @return JsonResponse
     */
    public function update(Request $request, Locker $locker): JsonResponse
    {
        $request->validate([
            'lockerID' => 'required',
            'status' => 'required',
            'date_time' => 'required',
        ]);

        $locker->update($request->all());

        return response()->json(['message' => 'Locker updated successfully', 'locker' => $locker]);
    }

    /**
     * Remove the specified locker from storage.
     *
     * @param Locker $locker
     * @return JsonResponse
     */
    public function destroy(Locker $locker): JsonResponse
    {
        $locker->delete();

        return response()->json(['message' => 'Locker deleted successfully']);
    }
}
