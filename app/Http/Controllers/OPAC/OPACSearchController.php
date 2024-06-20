<?php

namespace App\Http\Controllers\OPAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class OPACSearchController extends Controller
{
    // const URL = 'http://26.68.32.39:8000';
    const URL = 'http://127.0.0.1:8000';
    
    // BOOKS
    public function opacSearchBooks(Request $request){  
        $search = $request->input('search');

        $books = Material::select('accession', 'call_number', 'title', 'acquired_date', 'authors', 'image_url')
                    ->where('title', 'like', '%' . $search . "%")
                    ->orWhere('authors', 'like', '%' . $search . "%")
                    ->orderBy('date_published', 'desc')
                    ->paginate(24);

        foreach($books as $book) {
            if($book->image_url != null)
                $book->image_url = self::URL . Storage::url($book->image_url);

            $book->authors = json_decode($book->authors);
        }

        return $books;
    } 

    // PERIODICALS
    public function opacSearchPeriodicals(Request $request, $periodical_type){
        if (!in_array($periodical_type, ['0', '1', '2'])) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        $search = $request->input('search');

        $periodicals = Material::select('accession', 'title', 'date_published', 'authors', 'image_url')
                        ->where('material_type', 1)
                        ->where('periodical_type', $periodical_type)
                        ->where(function ($query) use ($search) {
                            $query->where('title', 'like', '%' . $search . "%")
                                ->orWhere('authors', 'like', '%' . $search . "%");
                        })
                        ->orderBy('date_published', 'desc')
                        ->paginate(24);

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }

        return $periodicals;
    }

    // ARTICLES
    public function opacSearchArticles(Request $request){
        $search = $request->input('search');;

        $articles = Material::select('accession', 'title', 'date_published', 'authors', 'abstract')
                ->where('title', 'like', '%' . $search . "%")
                ->orWhere('authors', 'like', '%' . $search . "%")
                ->orderBy('date_published', 'desc')
                ->paginate(24);

        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }

        return $articles;
    } 

    // PROJECTS
    public function opacSearchProjects(Request $request, $category) {

        if(!in_array($category, ['thesis', 'Classroom Based Action Research', 'capstone', 'feasibility study', 'research', 'dissertation'])){
            return response()->json(['error' => 'Page not found'], 404);
        }

        $search = $request->input('search');
        $filter = $request->input('filter', null);

        $projects = Project::select('accession', 'title', 'image_url', 'date_published', 'authors', 'program', 'keywords')
                ->where('category', $category)
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . "%")
                            ->orWhere('authors', 'like', '%' . $search . "%");
                            // ->orWhere('keywords', 'like', '%' . $search . "%");
                })
                ->wherehas('project_program', function($query) use($filter) {
                    if ($filter) {
                        $query->where('department_short', $filter);
                    }
                })
                ->orderByDesc('date_published')
                ->paginate(24);
    
        foreach ($projects as $project) {
            $project->authors = json_decode($project->authors);
            $project->keywords = json_decode($project->keywords);
            if ($project->image_url != null) {
                $project->image_url = self::URL .  Storage::url($project->image_url);
            }
        }

        return $projects;
    }

}
