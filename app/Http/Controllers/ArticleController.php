<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function getArticles()
    {
        // Retrieve all articles
        $articles = Article::all();

        return response()->json($articles);
    }
    /**
     * Retrieve articles filtered by material type.
     *
     * @param  string  $materialType
     * @return \Illuminate\Http\Response
     */
    public function getArticlesByMaterialType($materialType)
    {
        // Filter articles by material type
        $filteredArticles = Article::where('material_type', $materialType)->get();

        return response()->json($filteredArticles);
    }

    /**
     * Retrieve a specific article by its ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getArticle($id)
    {
        return Article::findOrFail($id);
    }
}
