<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function getAllBooks()
    {
        $books = Book::all();
        return response()->json(['books' => $books]);
    }

    public function getAllPeriodicals()
    {
        $periodicals = Periodical::all();
        return response()->json(['periodicals' => $periodicals]);
    }

    public function getAllArticles()
    {
        $articles = Article::all();
        return response()->json(['articles' => $articles]);
    }

    public function getAllProjects()
    {
        $projects = Project::all();
        return response()->json(['projects' => $projects]);
    }
    public function search(Request $request)
    {
        $searchTerm = $request->query('q');

        $materials = Material::where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('author', 'like', '%' . $searchTerm . '%')
            ->get();

        return response()->json(['materials' => $materials]);
    }
}
