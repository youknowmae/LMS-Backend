<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $announcements = Announcement::all();
        return response()->json(['announcements' => $announcements]);
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'date' => 'required',
            'author' => 'required',
            'blurb' => 'required',
            'file' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation for file upload
        ]);

        $announcement = new Announcement($request->all());

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::disk('public')->put('announcements', $file);
            $announcement->file_path = $path;
        }

        $announcement->save();

        return response()->json(['message' => 'Announcement created successfully', 'announcement' => $announcement], 201);
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
    public function update(Request $request, Announcement $announcement): JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'date' => 'required',
            'author' => 'required',
            'blurb' => 'required',
            'file' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation for file upload
        ]);

        $announcement->update($request->all());

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = Storage::disk('public')->put('announcements', $file);
            $announcement->file_path = $path;
            $announcement->save();
        }

        return response()->json(['message' => 'Announcement updated successfully', 'announcement' => $announcement]);
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function destroy(Announcement $announcement): JsonResponse
    {
        // Delete the file associated with the announcement if it exists
        if ($announcement->file_path) {
            Storage::disk('public')->delete($announcement->file_path);
        }

        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully']);
    }
}
