<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $announcements = Announcement::orderby('created_at', 'desc')->get();
        foreach($announcements as $announcement) {
            if($announcement->image != null)
                $announcement->image = 'http://localhost:8000' . Storage::url($announcement->image);
        }

        return response()->json(['announcements' => $announcements]);
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'title' => 'required',
            'category' => 'required',
            'content' => 'required',
            'date' => 'required',
            // 'author' => 'required',
            // 'blurb' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation for file upload
        ]);

        // $request->user()->id;
        
        if ($data->fails()) {
            return response()->json(['error' => $data->errors()]);
        }


        $announcement = new Announcement($request->all());

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::disk('public')->put('announcements', $file);
            
            $announcement->image = $path;
        }

        $announcement->save();

        return response()->json(['message' => 'Announcement created successfully'], 201);
    }

    /**
     * Display the specified announcement.
     *
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function show(Announcement $announcement): JsonResponse
    {
        return response()->json(['announcement' => $announcement]);
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param Request $request
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function update(Request $request, Announcement $announcement)
    {
        $data = Validator::make($request->all(), [
            'title' => 'required',
            'category' => 'required',
            'content' => 'required',
            'date' => 'required',
            // 'author' => 'required',
            // 'blurb' => 'required',
            'file' => 'nullable||mimes:jpg,jpeg,png,pdf|max:2048', // Example validation for file upload
        ]);

        // $request->user()->id;
        
        if ($data->fails()) {
            return response()->json(['error' => $data->errors()]);
        }

        $announcement->update($data->validated());

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::disk('public')->put('announcements', $file);
            $announcement->image = $path;
            $announcement->save();
        }

        return response()->json(['message' => 'Announcement updated successfully']);
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function destroy(Announcement $announcement)
    {
        // Delete the file associated with the announcement if it exists
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully']);
    }
}