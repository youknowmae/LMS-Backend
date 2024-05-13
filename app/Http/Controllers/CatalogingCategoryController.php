<?php

namespace App\Http\Controllers;

use App\Models\CatalogingCategory;
use Illuminate\Http\Request;

class CatalogingCategoryController extends Controller
{
    public function index()
    {
        $categories = CatalogingCategory::all();
        return response()->json(['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:cataloging_categories,name',
        ]);

        $category = CatalogingCategory::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category]);
    }

    public function update(Request $request, CatalogingCategory $category)
    {
        $request->validate([
            'name' => 'required|string|unique:cataloging_categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    public function destroy(CatalogingCategory $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
