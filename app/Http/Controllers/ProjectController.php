<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function getProjectCategoriesByDepartment($department)
    {
        // Retrieve projects based on the provided department
        $projects = Project::where('department', $department)->get();

        // Group projects by category
        $groupedProjects = $projects->groupBy('category');

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
}
