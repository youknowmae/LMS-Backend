<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function getLocations() {
        $locations = Location::orderBy('created_at', 'desc')->get();

        return $locations;
    }

    public function create(Request $request) {
        $location = Validator::make($request->all(), [
            'location' => 'required|string|max:10|unique:locations'
        ]);

        if($location->fails()) {
            return response()->json(['error' => $location->errors()]);
        }

        Location::create($location->validated());

        return response()->json(['success' => 'Location has been successfully created.'], 200);        
    }
}
