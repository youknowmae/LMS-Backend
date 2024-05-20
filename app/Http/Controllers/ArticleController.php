<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Exception, Str;

class ArticleController extends Controller
{
    const URL = 'http://192.168.10.122:8000';
    public function getArticles() {
        $articles = Article::orderByDesc('updated_at')->get();
        
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }
        
        return $articles;
    }

    public function getArticle($id) {
        return Article::find($id);
    }

    public function getByType($type) {
        $articles = Article::where('material_type', $type)->orderByDesc('updated_at')->get();
        
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }

        return $articles;
    }

    // FOR STUDENT PORTAL
    public function viewArticles() {
        $articles = Article::
        select(['material_type', 'title', 'authors', 'language', 'subject', 'date_published', 
        'publisher', 'volume', 'issue', 'abstract'])
        ->orderByDesc('created_at')->get();
        
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }
        
        return $articles;
    }

    public function viewArticle(int $id) {
        $article = Article::find($id, ['material_type', 'title', 'authors', 'language', 'subject', 'date_published', 
        'publisher', 'volume', 'issue', 'abstract']);
        
        $article->authors = json_decode($article->authors);
        
        return $article;
    }

    public function viewArticlesByType($type) {
        $articles = Article::where('material_type', $type)->orderByDesc('updated_at')->get();
        
        foreach($articles as $article) {
            $article->authors = json_decode($article->authors);
        }

        return $articles;
    }


    /* FOR PROCESSING */

    public function add(Request $request) {

        $request->validate([
            'accession' => 'required|string|max:255',
            'material_type' => 'required|string|max:15',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            // 'abstract' => 'required|mimes:jpeg,jpg,png|max:2048',
            'abstract' => 'required|string|max: 2048',
            'language' => 'required|string|max:15',
            'issue' => 'required|integer',
            'subject' => 'required|string|max:255',
            'volume' => 'required|integer',
            'publisher' => 'required|string|max:255',
            'pages' => 'required|string|max:25',
            'date_published' => 'required|date',
            'remarks' => 'nullable|string|max:255'
        ]);

        // return response()->json(['res' => 'nearly there'], 200);
        $model = new Article();

        $model->fill($request->except(['abstract']));

        if($request->image_url != null) {
            // foreach($request->abstract)
            $ext = $request->file('abstract')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            $path = $request->file('abstract')->store('public/images/articles');

            $model->image_url = $path;
        } 

        $model->title = Str::title($request->title);
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        $model->authors = json_encode($authors);

        $model->save();

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Added', $model->title, 'article', $model->material_type);

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'material_type' => 'nullable|string|max:15',
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

        $model = Article::findOrFail($id);
        $model->update($request->all());

        $model->title = Str::title($request->title);
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        $model->authors = json_encode($authors);

        $model->save();

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Updated', $model->title, 'article', null);

        return response()->json($model, 200);
    }
    
    public function delete(Request $request, $id) {
        $model = Article::findOrFail($id);
        $model->delete();

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, 'article', null);

        return response()->json(['Response' => 'Record Deleted'], 200);
    }

    //opac
    public function opacGetArticles(Request $request){
        $sort = $request->input('sort');

        $sort = $this->validateSort($sort);
        
        $articles = Article::select('id', 'title', 'date_published', 'authors', 'abstract')
                           ->orderBy($sort[0], $sort[1]);
                             
        return $articles->paginate(24);;
    }

    public function opacSearchArticles(Request $request){
        $search = $request->input('search');
        $sort = $request->input('sort', 'date_published desc');

        $articles = Article::select('id', 'title', 'date_published', 'authors', 'abstract');

        $sort = $this->validateSort($sort);

        $articles->where('title', 'like', '%' . $search . "%")->orWhere('authors', 'like', '%' . $search . "%");
        
        $articles->orderBy($sort[0], $sort[1]);

        return $articles->paginate(24);
    }   
}
