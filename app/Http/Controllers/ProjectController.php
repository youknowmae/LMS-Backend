<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function getProjects() {
        return Project::all();
    }

    // public function getProjectType($controller) {
    //     return Project::where('')
    // }

    public function getProject($id) {
        return Project::find($id);
    }

    public function add(Request $request) {
        $model = new Project();
        $model->fill($request->all());
        $model->save();

        return response()->json($model, 200);
    }
}
