<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function get(Request $request) {
        return Program::all();
    }
    
    public function addProgram(Request $request){
        $data = Validator::make($request->all(), [
            'program_short' => 'required|string|max:10',
            'program_full' => 'required|string|max:100',
            'category' => 'required|string|max:32',
            'department_short' => 'required|string|max:32',
            'department_full' => 'required|string|max:64'
        ]);

        if($data->fails()) {
            return response()->json(['errors', $data->errors()], 422);
        }

        Program::create($data->validated());

        return response()->json(['success' => 'Program has been created.'], 201);
    }

    public function viewDepartmentProgram($id)
    {
       $department = Department::with('programs')->findorfail($id);

       return $department;
    }
}
