<?php

namespace App\Http\Controllers\OPAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class OPACMaterialsController extends Controller
{
    // const URL = 'http://26.68.32.39:8000';
    const URL = 'http://127.0.0.1:8000';

    // BOOKS
    public function opacGetBooks() {        
        $books = Material::select('accession', 'title', 'date_published', 'authors', 'image_url')
                    ->where('material_type', 0)
                    ->orderBy('date_published', 'desc')
                    ->paginate(24);

        foreach ($books as $book) {
            $book->authors = json_decode($book->authors);
            if ($book->image_url != null) {
                $book->image_url = self::URL . Storage::url($book->image_url);
            }
        }
        
        return $books;
    }

    public function opacGetBook($id) {
        $book = Material::select('title', 'authors', 'publisher', 'image_url', 'volume', 'edition', 'pages', 'acquired_date', 'date_published', 'remarks', 'copyright', 
                                'location', 'call_number',  'status')
                                ->findOrFail($id);

        $book->authors = json_decode($book->authors);
        if($book->image_url != null)
            $book->image_url = self::URL . Storage::url($book->image_url);

        return $book;
    }

    // PERIODICAL
    public function opacGetPeriodicals($periodical_type){
        if (!in_array($periodical_type, ['0', '1', '2'])) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        $periodicals = Material::select('accession', 'title', 'date_published', 'authors', 'image_url')
                                    ->where('material_type', 1)
                                    ->where('periodical_type', $periodical_type)
                                    ->orderBy('date_published', 'desc')
                                    ->get();
        
        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }

        return $periodicals;
    }

    public function opacGetPeriodical($id) {
        $periodical = Material::select('title', 'authors', 'publisher', 'image_url', 'volume', 'edition', 'pages', 'acquired_date', 'date_published', 'remarks', 'copyright', 
                                    'language', 'issue')
                                    ->findOrFail($id);

        $periodical->authors = json_decode($periodical->authors);
        if($periodical->image_url)
            $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
        
        return $periodical;
    }

    // ARTICLES
    public function opacGetArticles(){
        $articles = Material::select('accession', 'title', 'date_published', 'authors', 'abstract')
                            ->where('material_type', 2)
                            ->orderBy('date_published', 'desc')
                            ->get();
                             
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }

        return $articles;
    }

    public function opacGetArticle($id) {
        $article = Material::select('title', 'authors', 'date_published', 'issue', 'abstract', 'pages')
                            ->findorfail($id);

        $article->authors = json_decode($article->authors);

        return $article;
    }

    // PROJECTS
    public function opacGetProjects(Request $request, $category){
        if(!in_array($category, ['thesis', 'Classroom Based Action Research', 'capstone', 'feasibility study', 'research', 'dissertation'])){
            return response()->json(['error' => 'Page not found'], 404);
        }

        // $filter = $request->input('filter', null); //set the filer to null if no filter is placed

        $projects = Project::select('accession', 'title', 'image_url', 'date_published', 'authors', 'program', 'keywords')
                    ->where('category', $category)
                    ->with('project_program:program_short,department_short')
                    // ->wherehas('project_program', function($query) use($filter) {
                    //     if ($filter) {
                    //         $query->where('department_short', $filter);
                    //     }
                    // })
                    ->orderbyDesc('date_published')
                    ->get();

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

        $project = Project::select('accession', 'title', 'authors', 'program', 'image_url', 'language', 'keywords', 'abstract', 'date_published')
                        ->with('project_program')
                        ->findOrfail($id);

        $project->authors = json_decode($project->authors);
        $project->keywords = json_decode($project->keywords);

        if ($project->image_url != null) {
            $project->image_url = self::URL .  Storage::url($project->image_url);
        }
        return $project;
    }

}