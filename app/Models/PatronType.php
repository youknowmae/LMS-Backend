<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatronType;
use Symfony\Component\HttpFoundation\Response;

class PatronTypeController extends Controller
{
    public function index()
    {
        $patronTypes = PatronType::all();
        return response()->json($patronTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'fines_if_overdue' => 'required|numeric',
            'days_allowed' => 'required|integer',
            'materials_allowed' => 'required|integer',
        ]);

        $patronType = new PatronType();
        $patronType->name = $request->name;
        $patronType->fines_if_overdue = $request->fines_if_overdue;
        $patronType->days_allowed = $request->days_allowed;
        $patronType->materials_allowed = $request->materials_allowed;
        $patronType->save();

        return response()->json(['message' => 'Patron type created successfully.'], Response::HTTP_CREATED);
    }

    public function update(Request $request, PatronType $patronType)
    {
        $request->validate([
            'name' => 'required',
            'fines_if_overdue' => 'required|numeric',
            'days_allowed' => 'required|integer',
            'materials_allowed' => 'required|integer',
        ]);

        $patronType->name = $request->name;
        $patronType->fines_if_overdue = $request->fines_if_overdue;
        $patronType->days_allowed = $request->days_allowed;
        $patronType->materials_allowed = $request->materials_allowed;
        $patronType->save();

        return response()->json(['message' => 'Patron type updated successfully.']);
    }

    public function destroy(PatronType $patronType)
    {
        $patronType->delete();

        return response()->json(['message' => 'Patron type deleted successfully.']);
    }
}
