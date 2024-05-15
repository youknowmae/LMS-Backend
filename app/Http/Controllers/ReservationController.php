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

    // Set status as pending
    $validatedData['status'] = 'pending';

    // Create reservation
    $reservations = Reservation::create($validatedData);
    return response()->json($reservations, 201);
}
    public function getByUserId($userId)
    {
        $reservations = Reservation::where('user_id', $userId)->with('book')->get();
        return response()->json($reservations);
    }
    

    // Other CRUD methods (show, edit, update, destroy) can be added here
}
