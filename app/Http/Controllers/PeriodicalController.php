<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodical;

class PeriodicalController extends Controller
{
    public function getPeriodicals() {
        return Periodical::all();
    }

    public function getPeriodicalByMaterialType($materialType)
    {
        // Filter articles by material type
        $filteredPeriodical = Periodical::where('material_type', $materialType)->get();

        return response()->json($filteredPeriodical);
    }
}
