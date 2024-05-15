<?php

namespace App\Http\Controllers;

use App\Models\CatalogingFilter;
use Illuminate\Http\Request;

class CatalogingFilterController extends Controller
{
    public function index()
    {
        $filters = CatalogingFilter::all();
        return response()->json(['filters' => $filters]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'location_1' => 'required|string',
            'location_2' => 'required|string',
            'location_3' => 'required|string',
        ]);

        $filter = CatalogingFilter::create([
            'category' => $request->category,
            'location_1' => $request->location_1,
            'location_2' => $request->location_2,
            'location_3' => $request->location_3,
        ]);

        return response()->json(['message' => 'Filter created successfully', 'filter' => $filter], 201);
    }

    public function update(Request $request, CatalogingFilter $filter)
    {
        $request->validate([
            'category' => 'string',
            'location_1' => 'string',
            'location_2' => 'string',
            'location_3' => 'string',
        ]);

        $filter->update($request->only(['category', 'location_1', 'location_2', 'location_3']));

        return response()->json(['message' => 'Filter updated successfully', 'filter' => $filter]);
    }

    public function destroy(CatalogingFilter $filter)
    {
        $filter->delete();
        return response()->json(['message' => 'Filter deleted successfully']);
    }
}
