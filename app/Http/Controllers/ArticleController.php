<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function getArticles() {
        return Article::all();
    }

    public function getArticle($id) {
        return Article::find($id);
    }

    public function add(Request $request) {
        $model = new Article();
        $model->fill($request->all());
        $model->save();

        $log = new CatalogingLogController();
        $log->add('Added', $model->title, 'article', null);

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {
        $model = Article::findOrFail($id);
        $model->update($request->all());
        $model->save();

        $log = new CatalogingLogController();
        $log->add('Updated', $model->title, 'article', null);

        return response()->json($model, 200);
    }
    
    public function delete($id) {
        $model = Article::findOrFail($id);
        $model->delete();

        $log = new CatalogingLogController();
        $log->add('Deleted', $model->title, 'article', null);

        return response('Record Deleted', 200);
    }
}
