<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patron;

class PatronController extends Controller
{
    public function index()
    {
        $patrons = Patron::all();
        
        return response()->json($patrons);
    }

    public function edit($id) {
        return Patron::findorfail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fines_if_overdue' => 'required|numeric',
            'days_allowed' => 'required|integer',
            'hours_allowed' => 'required|integer',
        ]);
        
        $patron = Patron::findorfail($id);

        $patron->fines_if_overdue = $request->fines_if_overdue;
        $patron->days_allowed = $request->days_allowed;
        $patron->hours_allowed = $request->hours_allowed;
        
        $patron->save();

        return response()->json(['message' => 'Patron type updated successfully.']);
    }
}
