<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CirculationLog;

class CirculationLogController extends Controller
{
    public function index()
    {
        $circulationLogs = CirculationLog::all();
        return response()->json(['circulationLogs' => $circulationLogs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patron_type' => 'required',
            'fines_if_overdue' => 'required|numeric',
            'days_allowed' => 'required|integer',
            'materials_allowed' => 'required|integer',
            'hours_allowed' => 'required|integer',
            'books_allowed' => 'required|integer',
        ]);

        $circulationLog = CirculationLog::create($request->all());

        return response()->json(['message' => 'Circulation log created successfully.', 'circulationLog' => $circulationLog], 201);
    }

    public function show($id)
    {
        $circulationLog = CirculationLog::findOrFail($id);
        return response()->json(['circulationLog' => $circulationLog]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patron_type' => 'required',
            'fines_if_overdue' => 'required|numeric',
            'days_allowed' => 'required|integer',
            'materials_allowed' => 'required|integer',
            'hours_allowed' => 'required|integer',
            'books_allowed' => 'required|integer',
        ]);

        $circulationLog = CirculationLog::findOrFail($id);
        $circulationLog->update($request->all());

        return response()->json(['message' => 'Circulation log updated successfully.', 'circulationLog' => $circulationLog]);
    }

    public function destroy($id)
    {
        $circulationLog = CirculationLog::findOrFail($id);
        $circulationLog->delete();

        return response()->json(['message' => 'Circulation log deleted successfully.']);
    }
}
