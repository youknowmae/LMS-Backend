<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodical;
use App\Models\Periodical;

class PeriodicalController extends Controller
{
    // Method to fetch all periodicals
    public function index()
    {
        $periodicals = Periodical::all();
        return response()->json($periodicals);
    }

    // Method to create a new periodical
    public function store(Request $request)
    {
        $periodical = Periodical::create($request->all());
        return response()->json($periodical, 201);
    }

    // Method to fetch a single periodical by ID
    public function show($id)
    {
        $periodical = Periodical::findOrFail($id);
        return response()->json($periodical);
    }

    // Method to update a periodical
    public function update(Request $request, $id)
    {
        $periodical = Periodical::findOrFail($id);
        $periodical->update($request->all());
        return response()->json($periodical, 200);
    }

    // Method to delete a periodical
    public function destroy($id)
    {
        Periodical::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function getPeriodicalByMaterialType($materialType)
    {
        // Filter articles by material type
        $filteredPeriodical = Periodical::where('material_type', $materialType)->get();

        return response()->json($filteredPeriodical);
    }
}
