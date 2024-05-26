<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
   
    public function index()
    {
        $reservations = Reservation::with('book')->get();
        return response()->json($reservations);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fine' => 'nullable|numeric',
            'status' => 'boolean',
            'type' => 'required|string', // New field type with value 'online'
        ]);

        // Add 'type' with value 'online' to validated data
        $validatedData['type'] = 'online';

        // Create reservation using automatic model binding
        $reservation = Reservation::create($validatedData);

        return response()->json($reservation, 201);
    }

    public function getUserById($id)
    {
        $reservations = Reservation::where('user_id', $id)->with('book')->get();
        return response()->json($reservations);
    }
    

    // Other CRUD methods (show, edit, update, destroy) can be added here
}
