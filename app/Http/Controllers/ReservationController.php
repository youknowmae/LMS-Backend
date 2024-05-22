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
    // $request->validate([
    //     'user_id' => 'required|exists:user,id',
    //     'book_id' => 'required|exists:book,id',
    //     'start_date' => 'required|date',
    //     'end_date' => 'required|date|after_or_equal:start_date',
    //     'fine' => 'nullable|numeric',
    //     'status' => 'boolean'
    // ]);

    $reservation = Reservation::create([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'fine' => $request->fine,
        'status' => $request->status ?? true,
        'type'=> $request->type,
    ]);

    return response()->json($reservation, 201);
}
    public function getUserById($id)
    {
        $reservations = Reservation::where('user_id', $id)->with('book')->get();
        return response()->json($reservations);
    }
    

    // Other CRUD methods (show, edit, update, destroy) can be added here
}
