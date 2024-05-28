<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollegeController extends Controller
{
    // Fetch all colleges (departments)
    public function getColleges()
    {
        return Department::all();
    }

    // Add a new college
    public function addCollege(Request $request)
    {
        $data = Validator::make($request->all(), [
            'department' => 'required|string|max:10|unique:departments',
            'full_department' => 'required|string|max:255'
        ]);

        if($data->fails()) {
            return response()->json(['error' => $data->errors()], 400);
        }

        $department = Department::create($data->validated());

        return response()->json(['message' => 'College added successfully!', 'department' => $department], 201);
    }
}