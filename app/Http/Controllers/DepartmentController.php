<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BorrowMaterialController;
use Exception;
use Storage;

use App\Models\Department;
use App\Models\Program;


//note
// All Departments
//['CCS', 'CAHS','CEAS','CHTM','CBA']


class DepartmentController extends Controller
{
    public function AddProgram(Request $request)
    {
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

    public function AddDepartment(Request $request)
    {
        $payload = $request->input('payload');

        //Create a new Department Instance and assigned values from payload
        $addDepartment = new Department();
        $addDepartment->department =$payload["department"];
        $addDepartment->full_department = $payload['full_department'];

        $addDepartment->save();

        return response()->json($addDepartment, 201);
    }
}
