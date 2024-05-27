<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function addProgram(Request $request){
        $data = Validator::make($request->all(), [
            'program' => 'required|string|max:10',
            'full_program' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id'
        ]);

        if($data->fails()) {
            return response()->json(['errors', $data->errors()], 400);
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