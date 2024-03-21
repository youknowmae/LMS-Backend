<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodical;

class PeriodicalController extends Controller
{
    public function getPeriodicals() {
        return Periodical::all();
    }

    // public function getPeriodicalType($controller) {
    //     return Periodical::where('')
    // }

    public function getPeriodical($id) {
        return Periodical::find($id);
    }

    public function add(Request $request) {
        $model = new Periodical();
        $model->fill($request->all());
        $model->save();

        return response()->json($model, 200);
    }
}
