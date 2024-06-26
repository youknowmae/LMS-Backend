<?php

namespace App\Http\Controllers\OPAC;

use App\Http\Controllers\Controller;
use App\Models\OpacViewCount;
use Illuminate\Support\Carbon;

class OpacViewCountController extends Controller
{
    public function getViewCount() {
        $today = Carbon::today()->format('Y-m-d');

        $viewCount = OpacViewCount::select('date', 'view_count')
                                ->where('date', $today)
                                ->first();
        
        if(!$viewCount){
            return response()->json(['view_count' => [
                'date' => $today,
                'view_count' => 0
            ]], 200);
        }

        return response()->json(['view_count' => $viewCount], 200);
    }

    public function updateViewCount() {
        $today = Carbon::today()->format('Y-m-d');
        
        $viewCount = OpacViewCount::where('date', $today)
                                ->first();

        if(!$viewCount){
            OpacViewCount::create([
                'date' => $today,
                'view_count' => 1
            ]);
            return response()->json(['success' => 'View tallied'], 200);
        }

        $viewCount->increment('view_count');

        return response()->json(['success' => 'View tallied'], 200);

    }

}
