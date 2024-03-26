<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
<<<<<<< Updated upstream
    //
=======
    public function getProjects() {
        return Project::all();
    }

    public function getByType($type) {
        return Project::where('type', $type)->get();
    }

    public function getProject($id) {
        return Project::find($id);
    }

    public function add(Request $request) {
        $model = new Project();
        $model->fill($request->all());
        $model->save();

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {
        $model = Project::find($id);
        $model->update($request->all());
        $model->save();

        return response()->json($model, 200);
    }

    public function delete($id) {
        $model = Project::find($id);
        $model->delete();

        return response()->json('Record Deleted', 200);
    }

    public function opacGetThesis(){
        return Project::where('type', 'thesis')->select('title', 'author', 'image_location')->paginate(25);
    }

    public function opacGetCapstone(){
        return Project::where('type', 'capstone')->select('title', 'author', 'image_location')->paginate(25);
    }

    public function opacGetResearch(){
        return Project::where('type', 'research')->select('title', 'author', 'image_location')->paginate(25);
    }

    public function opacGetDissertation(){
        return Project::where('type', 'dissertation')->select('title', 'author', 'image_location')->paginate(25);
    }

    public function opacGetFeasibility(){
        return Project::where('type', 'feasibility study')->select('title', 'author', 'image_location')->paginate(25);
    }
>>>>>>> Stashed changes
}
