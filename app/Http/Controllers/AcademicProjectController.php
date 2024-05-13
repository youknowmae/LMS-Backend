<?php

namespace App\Http\Controllers;

use App\Models\AcademicProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicProjectController extends Controller
{
    public function index()
    {
        $projects = AcademicProject::all();
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $project = AcademicProject::create($request->all());
        return response()->json($project, 201);
    }

    public function update(Request $request, AcademicProject $academicProject)
    {
        $academicProject->update($request->all());
        return response()->json($academicProject, 200);
    }

    public function destroy(AcademicProject $academicProject)
    {
        $academicProject->delete();
        return response()->json(null, 204);
    }

    /**
     * Filter academic projects based on college and course.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function filter(Request $request)
    {
        $request->validate([
            'college' => 'required',
            'course' => 'required',
        ]);

        $college = $request->input('college');
        $course = $request->input('course');

        $projects = AcademicProject::where('college', $college)
            ->where('course', $course)
            ->get();

        return response()->json(['projects' => $projects]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->query('q');

        $projects = AcademicProject::where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('author', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json(['projects' => $projects]);
    }
}


