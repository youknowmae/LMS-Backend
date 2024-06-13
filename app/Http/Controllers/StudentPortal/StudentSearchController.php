<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Project;

class StudentSearchController extends Controller
{
    // BOOKS
    public function searchBooks(Request $request)
    {
        // Retrieve the query parameter from the request
        $query = $request->input('query');

        // Check if the query parameter is empty or not provided
        if(empty($query)) {
            // Return a response indicating that the query is required
            return response()->json(['message' => 'Please provide a search query.'], 400);
        }
        
        // Search for books where the title contains the query string
        $books = Material::where('title', 'LIKE', "%{$query}%")->get();

        // Return the results as a JSON response
        return response()->json($books);
    }
    
    // PERIODICALS
    public function searchPeriodicals(Request $request) {
        // Retrieve the query parameter from the request
        $query = $request->input('query');
        
        $periodicals = Material::where('title', 'LIKE', "%{$query}%")
                                ->get();

        return response()->json($periodicals);
    }

    // ARTICLES
    public function searchArticles(Request $request)
    {
        // Retrieve the query parameter from the request
        $query = $request->input('query');
        
        // Search for books where the title contains the query string
        $articles = Material::where('title', 'LIKE', "%{$query}%")
                    ->get();
        
        // Return the results as a JSON response
        return response()->json($articles);
    }

    // PROJECTS
    public function searchProjects(Request $request)
    {
        // Retrieve the query parameter from the request
        $query = $request->input('query');
    
        // Check if the query parameter is empty or not provided
        if(empty($query)) {
            // Return a response indicating that the query is required
            return response()->json(['message' => 'Please provide a search query.'], 400);
        }
        
        // Search for books where the title contains the query string
        $Projects = Project::where('title', 'LIKE', "%{$query}%")->get();
    
        // Return the results as a JSON response
        return response()->json($Projects);
    }
}
