<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
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
}
