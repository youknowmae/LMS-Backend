<?php

namespace App\Http\Controllers;

use App\Models\CatalogingLog;
use Illuminate\Http\Request;
use App\Models\AcademicProject;
use App\Models\FilterCategory;

class CatalogingLogController extends Controller
{
    public function index()
    {
        $catalogingLogs = CatalogingLog::all();
        return response()->json(['cataloging_logs' => $catalogingLogs]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'action' => 'required|string',
            'title' => 'required|string',
            'type' => 'required|string',
            'location' => 'nullable|string',
        ]);

        // Construct log message
        $action = $validatedData['action'];
        $title = $validatedData['title'];
        $type = $validatedData['type'];
        $location = $validatedData['location'] ?? null;

        if (in_array($type, ['book', 'thesis', 'dissertation', 'feasibility study'])) {
            if ($action == 'Added') {
                $pre = 'to';
            } else {
                $pre = 'from';
            }
            $log = "$action '$title' $type $pre $location";
        } else {
            $log = "$action '$title' $type";
        }

        // Create cataloging log
        $catalogingLog = CatalogingLog::create([
            'action' => $action,
            'log' => $log
        ]);

        return response()->json(['message' => 'Cataloging log created successfully', 'data' => $catalogingLog]);
    }
    public function createFilter(Request $request)
    {
        $newFilter = FilterCategory::create([
            'location1' => $request->input('location1'),
            'location2' => $request->input('location2'),
            'location3' => $request->input('location3'),
        ]);

        return response()->json(['message' => 'Filter category created successfully', 'data' => $newFilter], 201);
    }
    public function addAcademicProject(Request $request)
    {
        $academicProject = AcademicProject::create([
            'college' => $request->input('college'),
            'courses' => $request->input('courses')
        ]);

        return response()->json(['message' => 'Academic project added successfully', 'data' => $academicProject], 201);
    }
}
