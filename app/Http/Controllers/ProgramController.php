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
}
