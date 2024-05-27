<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Department;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function AddProgram(Request $request){
        // Decode the JSON payload
        $payload = $request->input('payload');

        // Check if the department exists
        $department = Department::where('department', $payload['department'])->first();
        if (!$department) {
            return response()->json(['error' => 'Department not found'], 404);
        }

        // Create a new Program instance and assign values from the payload
        $addprogram = new Program();
        $addprogram->program = $payload['program'];
        $addprogram->department_id = $department->id; // Assign the department ID
        $addprogram->category = $payload['category'];
        $addprogram->full_program = $payload['full_program'];

        // Save the Program object
        $addprogram->save();

        // Return the created program
        return response()->json($addprogram, 201);
    }

    public function viewDepartmentProgram($id)
    {
       $department = Department::with('programs')->findorfail($id);

       return $department;
    }
        
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'program' => 'required|string|max:10',
    //         'full_program' => 'required|string|max:255',
    //         'category' => 'required|string|max:50',
    //         'department_id' => 'required|exists:departments,id'
    //     ]);

    //     $program = Program::create($validatedData);

    //     return response()->json(['message' => 'Program added successfully!', 'program' => $program], 201);
    // }
}