<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    // Fetch all colleges (departments)
    public function index()
    {
        return Department::all();
    }

    // Add a new college
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department' => 'required|string|max:10|unique:departments',
            'full_department' => 'required|string|max:255'
        ]);

        $department = Department::create($validatedData);

        return response()->json(['message' => 'College added successfully!', 'department' => $department], 201);
    }
}
