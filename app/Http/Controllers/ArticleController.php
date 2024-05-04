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
        $model = new Article();

        try {
            $model->fill($request->all());
            $model->save();
    
            $log = new CatalogingLogController();
            $log->add($request->user()->id, 'Added', $model->title, 'article', $model->material_type);
    
            return response()->json($model, 200);
        } catch (Exception $e) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.'], 400);
        }
    }

    public function update(Request $request, $id) {
        $model = Article::findOrFail($id);
        try {            
            $model->update($request->all());
            $model->save();

            $log = new CatalogingLogController();
            $log->add($request->user()->id, 'Updated', $model->title, 'article', null);
    
            return response()->json($model, 200);
        } catch (Exception $e) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
        }
    }
    
    public function delete(Request $request, $id) {
        $model = Article::findOrFail($id);
        $model->delete();

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, 'article', null);

        return response()->json(['Response' => 'Record Deleted'], 200);
    }
}
