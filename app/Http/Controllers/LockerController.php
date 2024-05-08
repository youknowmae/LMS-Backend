<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LockerHistory;

class LockerController extends Controller
{
    public function showAddForm()
    {
        return view('lockers.add');
    }

    public function add(Request $request)
    {
        $request->validate([
            'number_of_lockers' => 'required|integer|min:1',
        ]);

        // Store the history of locker additions
        LockerHistory::create([
            'number_of_lockers' => $request->number_of_lockers,
            'added_at' => now(),
        ]);

        return redirect()->route('lockers.add')->with('success', 'Lockers added successfully.');
    }
}
