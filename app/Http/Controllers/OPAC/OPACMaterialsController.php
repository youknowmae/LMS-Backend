<?php

namespace App\Http\Controllers\OPAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Project;
use Storage;

class OPACViewController extends Controller
{
    // const URL = 'http://26.68.32.39:8000';
    const URL = 'http://127.0.0.1:8000';

    // BOOKS
    public function opacGetBooks(Request $request) {        
        $sort = $request->input('sort', 'acquired_date desc'); 
    
        $sort = $this->validateSort($sort);
        
        if ($sort[0] === 'date_published') {
            $sort[0] = 'acquired_date';
        }
    
        $books = Material::select('id', 'call_number', 'title', 'acquired_date', 'authors', 'image_url')
                     ->orderBy($sort[0], $sort[1])
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
        $book = Material::select('title', 'location', 'call_number', 'copyright', 'authors', 'acquired_date', 'volume', 'pages', 'edition', 'remarks', 'status')
                        ->findOrFail($id);

        $book->authors = json_decode($book->authors);
        if($book->image_url != null)
            $book->image_url = self::URL . Storage::url($book->image_url);

        return $book;
    }

    // PERIODICAL
    public function opacGetPeriodicals(Request $request, $material_type){
        if (!in_array($material_type, ['0', '1', '2'])) {
            return response()->json(['error' => 'Page not found'], 404);
        }
        
        $sort = $request->input('sort', 'date_published desc');

        $sort = $this->validateSort($sort);

        $periodicals = Material::select('id', 'title', 'date_published', 'authors', 'image_url')
                                    ->where('material_type', $material_type)
                                    ->orderBy($sort[0], $sort[1])
                                    ->paginate(24);
        
        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }

        return $periodicals;
    }

    public function opacGetPeriodical($id) {
        $periodical = Material::select('title', 'language', 'copyright', 'authors', 'date_published', 'acquired_date', 'publisher', 'volume', 'issue', 'pages', 'remarks', 'status')
                            ->findOrFail($id);

        $periodical->authors = json_decode($periodical->authors);
        if($periodical->image_url)
            $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
        
        return $periodical;
    }

    // ARTICLES
    public function opacGetArticles(Request $request){
        $sort = $request->input('sort');

        $sort = $this->validateSort($sort);
        
        $articles = Material::select('id', 'title', 'date_published', 'authors', 'abstract')
                           ->orderBy($sort[0], $sort[1])
                           ->paginate(24);
                             
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

        $filter = $request->input('filter', null);

        $projects = Project::select('accession', 'title', 'image_url', 'date_published', 'authors', 'program', 'keywords')
                    ->where('category', $category)
                    ->wherehas('program', function($query) use($filter) {
                        if ($filter) {
                            $query->where('department_short', $filter);
                        }
                    })
                    ->orderbyDesc('date_published')
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

    public function opacGetProject($id){

        $project = Project::select('accession', 'title', 'authors', 'program', 'image_url', 'language', 'keywords', 'abstract', 'date_published')
                        ->with('program')
                        ->findOrfail($id);

        $project->authors = json_decode($project->authors);
        $project->keywords = json_decode($project->keywords);

        if ($project->image_url != null) {
            $project->image_url = self::URL .  Storage::url($project->image_url);
        }
        return $project;
    }

}
