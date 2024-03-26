<?php

namespace App\Http\Controllers;

use App\Models\Periodical;
use Illuminate\Http\Request;

class PeriodicalController extends Controller
{
    //
    public function getPeriodicals() {
        return Periodical::all();
    }

    public function getByType($type) {
        return Periodical::where('material_type', $type)->get();
    }

    public function getPeriodical($id) {
        return Periodical::find($id);
    }

    public function add(Request $request) {
        $model = new Periodical();
        $model->fill($request->all());
        $model->save();

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {
        $model = Periodical::find($id);
        $model->update($request->all());
        $model->save();

        return response()->json($model, 200);
    }

    public function delete($id) {
        $model = Periodical::find($id);
        $model->delete();

        return response()->json('Record Deleted', 200);
    }

    public function opacGetMagazines(){
        return Periodical::where('material_type', 'magazine')->select('title', 'copyright', 'author', 'image_location')->paginate(25);
    }

    public function opacGetjournals(){
        return Periodical::where('material_type', 'journal')->select('title', 'copyright', 'author', 'image_location')->paginate(25);
    }

    public function opacGetNewspapers(){
        return Periodical::where('material_type', 'newspaper')->select('title', 'copyright', 'author', 'image_location')->paginate(25);
    }
}
