<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class StudentMaterialController extends Controller
{
    const URL = 'http://26.68.32.39:8000';

    // View periodicals for student portal
    public function getPeriodicals() {
        $periodicals = Material::where('material_type', 1)
            ->select(['accession', 'title', 'authors', 'image_url', 'language', 'volume', 'issue', 'copyright', 'remarks', 'location'])
            ->orderByDesc('updated_at')
            ->get();

        foreach ($periodicals as $periodical) {
            $this->processImageURL($periodical);
            $this->decodeAuthors($periodical);
        }

        return response()->json($periodicals);
    }

    // Get periodicals by type
    public function getByType($type) {
        $periodicals = Material::where('material_type', $type)
            ->orderByDesc('updated_at')
            ->get();

        foreach ($periodicals as $periodical) {
            $this->processImageURL($periodical);
            $this->decodeAuthors($periodical);
        }

        return response()->json($periodicals);
    }

    // Get a specific periodical
    public function getPeriodical($accession) {
        $periodical = Material::where('material_type', 1)
            ->where('accession', $accession)
            ->firstOrFail();

        $this->processImageURL($periodical);
        $this->decodeAuthors($periodical);

        return response()->json($periodical);
    }

    // Get periodical by periodical type
    public function getPeriodicalByPeriodicalType($periodicalType) {
        $typeMapping = [
            'journal' => 0,
            'magazine' => 1,
            'newspaper' => 2
        ];

        if (!array_key_exists($periodicalType, $typeMapping)) {
            return response()->json(['message' => 'Invalid periodical type.'], 400);
        }

        $typeValue = $typeMapping[$periodicalType];

        $filteredPeriodicals = Material::where('periodical_type', $typeValue)
            ->where('material_type', 1)
            ->get();

        foreach ($filteredPeriodicals as $periodical) {
            $this->processImageURL($periodical);
            $this->decodeAuthors($periodical);
        }

        return response()->json($filteredPeriodicals);
    }

    // Search periodicals
    public function searchPeriodicals(Request $request) {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['message' => 'Please provide a search query.'], 400);
        }

        $periodicals = Material::where('title', 'LIKE', "%{$query}%")
            ->where('material_type', 1)
            ->get();

        foreach ($periodicals as $periodical) {
            $this->processImageURL($periodical);
            $this->decodeAuthors($periodical);
        }

        return response()->json($periodicals);
    }

    // View articles for student portal
    public function viewArticles() {
        $articles = Material::where('material_type', 2)
            ->select(['accession', 'title', 'authors', 'language', 'subject', 'date_published', 'publisher', 'volume', 'issue', 'abstract'])
            ->orderByDesc('created_at')
            ->get();

        foreach ($articles as $article) {
            $this->decodeAuthors($article);
        }

        return response()->json($articles);
    }

    // Get a specific article
    public function viewArticle($accession) {
        $article = Material::where('material_type', 2)
            ->where('accession', $accession)
            ->firstOrFail();

        $this->decodeAuthors($article);

        return response()->json($article);
    }

    // View articles by type
    public function viewArticlesByType($type) {
        $typeMapping = [
            'journal' => 0,
            'magazine' => 1,
            'newspaper' => 2
        ];

        if (!array_key_exists($type, $typeMapping)) {
            return response()->json(['message' => 'Invalid article type.'], 400);
        }

        $typeValue = $typeMapping[$type];

        $articles = Material::where('periodical_type', $typeValue)
            ->where('material_type', 2)
            ->orderByDesc('updated_at')
            ->get();

        foreach ($articles as $article) {
            $this->decodeAuthors($article);
        }

        return response()->json($articles);
    }

    // Search articles
    public function searchArticles(Request $request) {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['message' => 'Please provide a search query.'], 400);
        }

        $articles = Material::where('title', 'LIKE', "%{$query}%")
            ->where('material_type', 2)
            ->get();

        foreach ($articles as $article) {
            $this->decodeAuthors($article);
        }

        return response()->json($articles);
    }

    // View books for student portal
    public function viewBooks() {
        $books = Material::where('material_type', 0)
            ->select(['accession', 'call_number', 'title', 'acquired_date', 'authors', 'image_url'])
            ->orderByDesc('date_published')
            ->get();

        foreach ($books as $book) {
            $this->processImageURL($book);
            $this->decodeAuthors($book);
        }

        return response()->json($books);
    }

    // View a specific book
    public function viewBook($accession) {
        $book = Material::where('material_type', 0)
            ->where('accession', $accession)
            ->firstOrFail(['accession', 'title', 'authors', 'image_url', 'call_number', 'acquired_date', 'date_published', 'remarks', 'copyright', 'price', 'status', 'inventory_status', 'volume', 'pages', 'edition']);

        $this->processImageURL($book);
        $this->decodeAuthors($book);

        return response()->json($book);
    }

    // Search books
    public function searchBooks(Request $request) {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json(['message' => 'Please provide a search query.'], 400);
        }

        $books = Material::where('title', 'LIKE', "%{$query}%")
            ->where('material_type', 0)
            ->get();

        foreach ($books as $book) {
            $this->processImageURL($book);
            $this->decodeAuthors($book);
        }

        return response()->json($books);
    }

    // Get all projects
    public function getProjects() {
        $projects = Project::orderByDesc('date_published')
            ->get();

        foreach ($projects as $project) {
            $this->processImageURL($project);
            $this->decodeAuthors($project);
        }

        return response()->json($projects);
    }

    // Get projects by program
    public function getProjectsByProgram($program) {
        $projects = Project::where('program', $program)
            ->orderByDesc('date_published')
            ->get();

        foreach ($projects as $project) {
            $this->processImageURL($project);
            $this->decodeAuthors($project);
        }

        return response()->json($projects);
    }

    // Get projects by category
    public function getProjectsByCategory($category) {
        $projects = Project::where('category', $category)
            ->orderByDesc('date_published')
            ->get();

        foreach ($projects as $project) {
            $this->processImageURL($project);
            $this->decodeAuthors($project);
        }

        return response()->json($projects);
    }

    // Helper method to process image URL
    private function processImageURL(&$material) {
        if ($material->image_url) {
            $material->image_url = self::URL . Storage::url($material->image_url);
        }
    }

    // Helper method to decode authors
    private function decodeAuthors(&$material) {
        $material->authors = json_decode($material->authors, true);
    }
}
