<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        return response()->json($announcements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'date' => 'required|date',
            'author' => 'required',
            'blurb' => 'required',
            'file' => 'required|file',
        ]);

        $announcement = new Announcement($request->all());
        $announcement->title = $request->title;
        $announcement->category = $request->category;
        $announcement->date = $request->date;
        $announcement->author = $request->author;
        $announcement->blurb = $request->blurb;
        $announcement->file_path = $request->file('file');
        $announcement->save();

        return response()->json(['message' => 'Announcement created successfully.'], Response::HTTP_CREATED);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'date' => 'required|date',
            'author' => 'required',
            'blurb' => 'required',
            'file' => 'file'
        ]);

        $announcement->update($request->all());
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($announcement->file_path);
            $announcement->file_path = $request->file('file');
            $announcement->save();
        }

        $announcement->title = $request->title;
        $announcement->category = $request->category;
        $announcement->date = $request->date;
        $announcement->author = $request->author;
        $announcement->blurb = $request->blurb;
        $announcement->save();

        return response()->json(['message' => 'Announcement updated successfully.']);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json(['message' => 'Announcement deleted successfully.']);
    }
}
