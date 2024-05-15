<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    // Method to fetch all announcements
    public function index()
    {
        $announcements = Announcement::all();
        return response()->json($announcements);
    }

    // Method to create a new announcement
    public function store(Request $request)
    {
        $announcement = Announcement::create($request->all());
        return response()->json($announcement, 201);
    }

    // Method to fetch a single announcement by ID
    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        return response()->json($announcement);
    }

    // Method to update an announcement
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->all());
        return response()->json($announcement, 200);
    }

    // Method to delete an announcement
    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
