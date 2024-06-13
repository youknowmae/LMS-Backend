<?php

namespace App\Http\Controllers\Cataloging;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use Exception, Str, Storage;

class ArticleController extends Controller
{
    // const URL = 'http://26.68.32.39:8000';
    const URL = 'http://127.0.0.1:8000';

    public function getArticles() {
        $articles = Material::where([['material_type', 2]])
        ->orderByDesc('updated_at')
        ->get(['accession', 'title', 'author', 'publisher', 'copyright']);

        foreach($articles as $article) {
            if($article->image_url != null)
                $article->image_url = self::URL .  Storage::url($article->image_url);
            
            $article->authors = json_decode($article->authors);
        }
        
        return $articles;
    }

    public function getByType($type) {
        $articles = Material::where([['material_type', 2], ['periodical_type', $type]])
        ->orderByDesc('updated_at')
        ->get(['accession', 'title', 'authors', 'publisher', 'date_published']);

        foreach($articles as $article) {
            if($article->image_url != null)
                $article->image_url = self::URL . Storage::url($article->image_url);

            $article->authors = json_decode($article->authors);
        }
        
        return $articles;
    }

    public function getArticle($id) {
        $article = Material::findOrFail($id);
        $article->authors = json_decode($article->authors);

        return $article;
    }

    // FOR STUDENT PORTAL
    public function viewArticles() {
        $articles = Material::
        select(['material_type', 'title', 'authors', 'language', 'subject', 'date_published', 
        'publisher', 'volume', 'issue', 'abstract'])
        ->orderByDesc('created_at')->get();
        
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }
        
        return $articles;
    }

    public function viewArticle(int $id) {
        $article = Material::find($id, ['material_type', 'title', 'authors', 'language', 'subject', 'date_published', 
        'publisher', 'volume', 'issue', 'abstract']);
        
        $article->authors = json_decode($article->authors);
        
        return $article;
    }

    public function viewArticlesByType($type) {
        $articles = Material::where('material_type', $type)->orderByDesc('updated_at')->get();
        
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }

        return $articles;
    }


    /* FOR PROCESSING */

    public function add(Request $request) {

        $request->validate([
            'accession' => 'required|string|max:255',
            'periodical_type' => 'required|integer',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'abstract' => 'required|string|max: 4096',
            'language' => 'required|string|max:15',
            'issue' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'volume' => 'required|string|max:50',
            'publisher' => 'required|string|max:255',
            'pages' => 'required|string|max:25',
            'date_published' => 'required|date',
            'remarks' => 'nullable|string|max:255'
        ]);

        // return response()->json(['res' => 'nearly there'], 200);
        $model = new Material();
        $model->material_type = 2;

        $model->fill($request->all());

        $model->title = Str::title($request->title);
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        $model->authors = json_encode($authors);

        $model->save();

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'periodical_type' => 'required|integer',
            'title' => 'nullable|string|max:255',
            'authors' => 'nullable|string|max:255',
            'abstract' => 'nullable|string|max:2048',
            'language' => 'nullable|string|max:15',
            'issue' => 'nullable|integer',
            'subject' => 'nullable|string|max:255',
            'volume' => 'nullable|integer',
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|string|max:25',
            'date_published' => 'nullable|date',
            'remarks' => 'nullable|string|max:255'
        ]);

        $model = Material::findOrFail($id);
        $model->update($request->all());

        $model->title = Str::title($request->title);
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        $model->authors = json_encode($authors);

        $model->save();

        return response()->json($model, 200);
    }
    
    public function delete(Request $request, $id) {
        $model = Material::findOrFail($id);
        $model->delete();

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, 'article', null);

        return response()->json(['Response' => 'Record Deleted'], 200);
    }

    //opac
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

    public function opacSearchArticles(Request $request){
        $search = $request->input('search');
        $sort = $request->input('sort', 'date_published desc');

        $sort = $this->validateSort($sort);

        $articles = Material::select('id', 'title', 'date_published', 'authors', 'abstract')
                ->where('title', 'like', '%' . $search . "%")
                ->orWhere('authors', 'like', '%' . $search . "%")
                ->orderBy($sort[0], $sort[1])
                ->paginate(24);

        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }

        return $articles;
    } 
    
    //ARTICLE
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
}
