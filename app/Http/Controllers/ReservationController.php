<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function create()
    {
        // Logic for displaying create reservation form
    }

    public function store(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'user_id' => 'required',
            'book_id' => 'required',
            'title' => 'required',
            'author' => 'required',
            'location' => 'required',
            'date_requested' => 'required|date',
            'number_of_books' => 'required|integer|min:1',
            'date_of_expiration' => 'required|date|after_or_equal:date_requested',
            'fine' => 'nullable|numeric|min:0',
        ]);

        // Create reservation
        Reservation::create($validatedData);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    public function index()
    {
        $reservations = Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    // Other CRUD methods (show, edit, update, destroy) can be added here
}
