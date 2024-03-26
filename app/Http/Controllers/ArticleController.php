<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    //
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

        return response()->json($model, 200);
    }

    public function update(Request $request, $id) {
        $model = Article::find($id);
        $model->update($request->all());
        $model->save();

        return response()->json($model, 200);
    }
    
    public function delete($id) {
        $model = Article::find($id);
        $model->delete();

        return response()->json('Record Deleted', 200);
    }

    public function opacGetArticles(){
        return Article::select('title', 'publisher', 'author', 'image_location')->paginate(25);
    }
}
