<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use Symfony\Component\HttpFoundation\Response;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnel = Personnel::all();
        return response()->json($personnel);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'permitted_access' => 'required|array',
        ]);

        $personnel = new Personnel();
        $personnel->name = $request->name;
        $personnel->password = $request->password;
        $personnel->permitted_access = $request->permitted_access;
        $personnel->save();

        return response()->json(['message' => 'Personnel created successfully.'], Response::HTTP_CREATED);
    }

    public function update(Request $request, Personnel $personnel)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'permitted_access' => 'required|array',
        ]);

        $personnel->name = $request->name;
        $personnel->password = $request->password;
        $personnel->permitted_access = $request->permitted_access;
        $personnel->save();

        return response()->json(['message' => 'Personnel updated successfully.']);
    }

    public function destroy(Personnel $personnel)
    {
        $personnel->delete();

        return response()->json(['message' => 'Personnel deleted successfully.']);
    }
}
