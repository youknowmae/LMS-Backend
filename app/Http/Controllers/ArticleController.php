<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Exception;

class ArticleController extends Controller
{
    public function getArticles() {
        $articles = Article::all()->orderByDesc('updated_at');
        
        return $articles;
    }

    public function getArticle($id) {
        return Article::find($id);
    }

    public function getByType($type) {
        $articles = Article::where('material_type', $type)->orderByDesc('updated_at')->get();
        
        return $articles;
    }

    public function add(Request $request) {

        $request->validate([
            'material_type' => 'required|string|max:15',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'abstract' => 'required|string|max:2048',
            'language' => 'required|string|max:15',
            'issue' => 'required|integer',
            'subject' => 'required|string|max:255',
            'volume' => 'required|integer',
            'publisher' => 'required|string|max:255',
            'pages' => 'required|string|max:25',
            'date_published' => 'required|date',
            'remarks' => 'nullable|string|max:255'
        ]);

        $model = new Article();

        $model->fill($request->all());
        $model->save();

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Added', $model->title, 'article', $model->material_type);

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'material_type' => 'nullable|string|max:15',
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
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
}
