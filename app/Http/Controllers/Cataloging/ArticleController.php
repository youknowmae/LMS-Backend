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

    public function add(Request $request) {

        $request->validate([
            'accession' => 'required|string|max:255',
            'periodical_type' => 'required|integer',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'pages' => 'required|string|max:25',
            'abstract' => 'required|string|max: 4096',
            'volume' => 'required|string|max:50',
            'issue' => 'required|string|max:50',
            'language' => 'required|string|max:15',
            'subject' => 'required|string|max:255',
            'date_published' => 'required|date'
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
            'accession' => 'required|string|max:255',
            'periodical_type' => 'required|integer',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'pages' => 'required|string|max:25',
            'abstract' => 'required|string|max: 4096',
            'volume' => 'required|string|max:50',
            'issue' => 'required|string|max:50',
            'language' => 'required|string|max:15',
            'subject' => 'required|string|max:255',
            'date_published' => 'required|date'
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
}
