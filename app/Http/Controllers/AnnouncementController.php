<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    const URL = 'http://localhost:8000';

    public function index()
    {
        $announcements = Announcement::orderby('created_at', 'desc')->get();
        foreach($announcements as $announcement) {
            if($announcement->image_url != null)
                $announcement->image_url = self::URL . Storage::url($announcement->image_url);
        }

        return $announcements;
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'title' => 'required|string|max:128',
            'category' => 'required|string|max:128',
            'text' => 'required|string|max:8192',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation for file upload
        ]);

        $user_id =  $request->user()->id;
        
        if ($data->fails()) {
            return response()->json(['error' => $data->errors()], 422);
        }

        $announcement = new Announcement($data->validated());
        $announcement->author_id = $user_id;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::disk('public')->put('announcements', $file);
            
            $announcement->image_url = $path;
        }

        $announcement->save();

        if($announcement->image_url != null)
            $announcement->image_url = self::URL . Storage::url($announcement->image_url);
        else {
            $announcement->image_url = null;
        }

        return response()->json(['success' => $announcement], 201);
    }

    public function show(Announcement $announcement)
    {
        return $announcement;
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = Validator::make($request->all(), [
            'title' => 'required|string|max:128',
            'category' => 'required|string|max:128',
            'text' => 'required|string|max:8192',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation for file upload
        ]);

        // $request->user()->id;
        
        if ($data->fails()) {
            return response()->json(['error' => $data->errors()], 422);
        }

        $announcement->update($data->validated());

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::disk('public')->put('announcements', $file);
            $announcement->image_url = $path;
            $announcement->save();
        }

        if($announcement->image_url != null)
            $announcement->image_url = self::URL . Storage::url($announcement->image_url);
        else {
            $announcement->image_url = null;
        }

        return response()->json(['success' => $announcement], 201);
    }

    public function destroy(Announcement $announcement)
    {
        // Delete the file associated with the announcement if it exists
        if ($announcement->image_url) {
            Storage::disk('public')->delete($announcement->image_url);
        }

        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully']);
    }
}
