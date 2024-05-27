<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Department;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function get() {
        return Program::with('department')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'program' => 'required|string|max:10',
            'full_program' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id'
        ]);

        $program = Program::create($validatedData);

        return response()->json(['message' => 'Program added successfully!', 'program' => $program], 201);
    }

    //note
    // Route::get('CBA-list',[ProgramController::class,'viewcba']);
    // Route::get('CCS-list',[ProgramController::class,'viewccs']);
    // Route::get('CEAS-list',[ProgramController::class,'viewceas']);
    // Route::get('CAHS-list',[ProgramController::class,'viewcahs']);
    // Route::get('CHTM-list',[ProgramController::class,'viewchtm']);

    public function viewDepartmentProgram($department)
    {
       
        // Retrieve the department by its name
        $departmentModel = Department::where('department', $department)->first();

        // If the department is found, retrieve the programs associated with it
        if ($departmentModel) {
            $programs = $departmentModel->programs()->pluck('full_program');
            return response()->json(['department' => $department, 'programs' => $programs]);
        } else {
            // Handle invalid department route, maybe return a 404 response
            return response()->json(['error' => 'Department not found'], 404);
        }
    }
}