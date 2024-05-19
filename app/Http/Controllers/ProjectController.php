<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\Project;
use Exception, DB, Storage, Str;

class ProjectController extends Controller
{
    const URL = 'http://192.168.68.124:8000';
    public function getProjects() {
        $projects = Project::with(['program'])->orderByDesc('created_at')->get();

        foreach($projects as &$project) {
            if($project->image_url != null)
                $project->image_url = self::URL .  Storage::url($project->image_url);

            $project->authors = json_decode($project->authors);
            $project->keywords = json_decode($project->keywords);
        }
        return $projects;
    }

    public function getByDepartment($department) {
        // for getting by departments -> student portal
        $projects = Project::with(['program'])->orderByDesc('created_at')->get();

        $projects = $projects->where('program', '=', $department);
        
        $projects->each(function ($project) {
            $project->projectAuthors = $project->projectAuthors->sortBy('name')->values();
        });

        foreach($projects as $project) {
            if($project->image_url != null)
                $project->image_url = self::URL .  Storage::url($project->image_url);

            $project->authors = json_decode($project->authors);
        }
        return $projects;
    }
    
    public function getProject($id) {
        return Project::find($id);
    }

    // FOR STUDENT PORTAL
    public function viewProjectsByDepartment(string $department) {
        $projects = Project::with(['program'])->orderByDesc('created_at')->get();

        // return response()->json($projects);
        $projects_array = [];
        foreach($projects as $project) {
            if($project->program->department == $department) {
                $image_url = null;
                if($project->image_url != null)
                    $image_url = self::URL .  Storage::url($project->image_url);

                if (isset($projects_array[$project->category])) {

                    // there is a match
                    $projects_array[$project->category][] = [
                        'authors' => json_decode($project->authors),
                        'title' => $project->title, 
                        'category' => $project->category,
                        'program' => $project->program->program,
                        'image_url' => $image_url,
                        'date_published' => $project->date_published,
                        'language' => $project->language,
                        'abstract' => $project->abstract
                    ];
                } else {
                    
                    // there is no match
                    $projects_array[$project->category] = [
                        [
                            'authors' => json_decode($project->authors),
                            'title' => $project->title, 
                            'category' => $project->category,
                            'program' => $project->program->program,
                            'image_url' => $image_url,
                            'date_published' => $project->date_published,
                            'language' => $project->language,
                            'abstract' => $project->abstract
                        ]
                    ];
                }
            }
        }

        return $projects_array;
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
            'keywords' => 'required|string|max:1024',
            // 'abstract' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'abstract' => 'required|string|max:2048',
            'image_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        

        // return 'after validation';
        $model = new Project();
        try {
            $model->fill($request->except('image_url', 'authors'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
        }

        // ADD COVER
        $ext = $request->file('image_url')->extension();

        // Check file extension and raise error
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
        }

        // Store image and save path
        $path = $request->file('image_url')->store('public/images/projects/covers');

        $model->image_url = $path;

        // // ADD ABSTRACT IMAGE
        // $ext = $request->file('abstract')->extension();

        // // Check file extension and raise error
        // if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
        //     return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
        // }

        // // Store image and save path
        // $path = $request->file('image_url')->store('public/images/projects/abstracts');

        // $model->abstract = $path;

        // ADD AUTHORS
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        sort($authors);

        $model->authors = json_encode($authors);
        
        $model->save();

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
            'abstract' => 'nullable|string|max:2048',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = Project::findOrFail($id);

        try {
            $model->fill($request->except('image_url', 'authors'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.'], 400);
        }

        if($request->image_url != null) {
            $ext = $request->file('image_url')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $materials = Project::withTrashed()->where('image_url', '=', $model->image_url)->count();

                if(!empty($model->image_url) && $materials == 1) {
                    
                    $image = new ImageController();
                    $image->delete($model->image_url);
                }
                
                $path = $request->file('image_url')->store('public/images/projects');
                $model->image_url = $path;

            } catch (Exception $e) {
                // add function
            }
        }
        
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        sort($authors);

        $model->authors = json_encode($authors);
        
        $model->save();

        $type = strtolower($model->type);
        $program = Program::find($model->program_id)->program;

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Updated', $model->title, $type, $program);

        return response()->json($model, 200);
    }

    public function delete(Request $request, $id) {
        $model = Project::findOrFail($id);
        $materials = Project::withTrashed()->where('image_url', '=', $model->image_url)->count();

        if(!empty($model->image_url) && $materials == 1) {
            
            $image = new ImageController();
            $image->delete($model->image_url);
        }
        $model->delete();
        
        $type = strtolower($model->type);
        $program = Program::find($model->program_id)->program;

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, $type, $program);

        return response()->json(['Response' => 'Record Archived'], 200);
    }

    // PENDING APPROVAL
    
    public function getProjectCategoriesByDepartment($department)
    {
        // Retrieve projects based on the provided department
        $projects = Project::with('program')->get();
        
        $newProjects = [];
        foreach($projects as $project) {
            if($project->program->department == $department) {
                array_push($newProjects, $project);
            }
        }

        $newProjects = collect($newProjects);
        
        // Group projects by category
        $groupedProjects = $newProjects->groupBy('category');

        // Get the category names
        $categories = $groupedProjects->keys();

        // Return an array containing category names and their respective projects
        $projectCategories = [];
        foreach ($categories as $category) {
            $projectCategories[] = [
                'category' => $category,
                'projects' => $groupedProjects[$category],
            ];
        }

        return response()->json($projectCategories);
    }

    public function opacGetProjects($category){
        // if(!in_array($category, ['thesis', 'Classroom Based Action Research', 'capstone', 'feasibility study', 'research', 'dissertation'])){
        //     return response()->json(['error' => 'Page not found'], 404);
        // }

        $projects = Project::select('id', 'title', 'image_url', 'date_published', 'authors')
                   ->where('category', $category)
                   ->orderby('date_published', 'desc')
                   ->paginate(24);

        if ($projects->isEmpty()) {
            return response()->json(['message' => 'No projects found'], 404);
        }

        foreach ($projects as $project) {
            $project->authors = json_decode($project->authors);
            $project->keywords = json_decode($project->keywords);
            if ($project->image_url != null) {
                $project->image_url = self::URL .  Storage::url($project->image_url);
            }
        }

        return $projects;

    }

    public function opacGetProject($id){

        $project =Project::with('program')->findOrfail($id);

        $project->authors = json_decode($project->authors);
        $project->keywords = json_decode($project->keywords);

        if ($project->image_url != null) {
            $project->image_url = self::URL .  Storage::url($project->image_url);
        }
        return $project;
    }

    public function opacSearch(Request $request, $category) {
        if(!in_array($category, ['thesis', 'Classroom Based Action Research', 'capstone', 'feasibility study', 'research', 'dissertation'])){
            return response()->json(['error' => 'Page not found'], 404);
        }

        $search = $request->input('search');
        $sort = $request->input('sort', 'date_published desc');
    
        $projects = Project::select('id', 'title', 'date_published', 'image_url', 'abstract', 'authors')    
                             ->where('category', $category);

        $sort = $this->validateSort($sort);

        $projects->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . "%")
                  ->orWhere('authors', 'like', '%' . $search . "%");
        });
        
        $projects->orderBy($sort[0], $sort[1]);

        return $projects->paginate(24);
    }
}
