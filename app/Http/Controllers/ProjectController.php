<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\Project;
use Exception;
use DB;
use Storage;

class ProjectController extends Controller
{
    public function getProjects() {
        $projects = Project::with(['program', 'projectAuthors'])->orderByDesc('created_at')->get();

        $projects->each(function ($project) {
            $project->projectAuthors = $project->projectAuthors->sortBy('name')->values();
        });

        foreach($projects as $project) {
            if($project->image_location != null)
                $project->image_location = 'http://localhost:8000' . Storage::url($project->image_location);
        }
        return $projects;
    }

    public function getByType($type) {
        return Project::where('type', $type)->get();
    }
    
    public function getProject($id) {
        return Project::find($id);
    }

    public function add(Request $request) {

        // VALIDATION
        $request->validate([
            'program_id' => 'required|integer|max:255',
            'category' => 'required|string|max:125',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:1024',
            'language' => 'required|string|max:25',
            'date_published' => 'required|date',
            'abstract' => 'required|string|max:2048',
            'image_location' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = new Project();
        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
        }

        $ext = $request->file('image_location')->extension();

        // Check file extension and raise error
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
        }

        // Store image and save path
        $path = $request->file('image_location')->store('public/images/projects');

        $model->image_location = $path;
        $model->save();
        
        $authors = json_decode($request->authors, true);

        foreach($authors as $author) {
            DB::table('project_authors')->insert(
                ['name' => $author['value'], 'project_id' => $model->id]
            );
        }

        $type = strtolower($model->type);
        $program = Program::find($model->program_id)->program;

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Added', $model->title, $type, $program);

        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        
        // VALIDATION
        $request->validate([
            'program_id' => 'required|integer|max:255',
            'category' => 'required|string|max:125',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:1024',
            'language' => 'required|string|max:25',
            'date_published' => 'required|date',
            'abstract' => 'required|string|max:2048',
            'image_location' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = Project::findOrFail($id);

        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.'], 400);
        }

        if($request->image_location != null) {
            $ext = $request->file('image_location')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $materials = Project::withTrashed()->where('image_location', '=', $model->image_location)->count();

                if(!empty($model->image_location) && $materials == 1) {
                    
                    $image = new ImageController();
                    $image->delete($model->image_location);
                }
                
                $path = $request->file('image_location')->store('public/images/projects');
                $model->image_location = $path;

            } catch (Exception $e) {
                // add function
            }
        }
        
        $model->save();

        $type = strtolower($model->type);
        $program = Program::find($model->program_id)->program;

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Updated', $model->title, $type, $program);

        return response()->json($model, 200);
    }

    public function delete(Request $request, $id) {
        $model = Project::findOrFail($id);
        $materials = Project::withTrashed()->where('image_location', '=', $model->image_location)->count();

        if(!empty($model->image_location) && $materials == 1) {
            
            $image = new ImageController();
            $image->delete($model->image_location);
        }
        $model->delete();
        $model->delete();
        
        $type = strtolower($model->type);
        $program = Program::find($model->program_id)->program;

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, $type, $program);

        return response()->json(['Response' => 'Record Archived'], 200);
    }

    

}
