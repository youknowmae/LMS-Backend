<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Retrieve all articles.
     *
     * @return \Illuminate\Http\Response
     */
    public function getArticles()
    {
        return Article::all();
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
