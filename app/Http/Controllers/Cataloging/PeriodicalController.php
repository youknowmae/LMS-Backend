<?php

namespace App\Http\Controllers\Cataloging;

use App\Models\Material;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Storage, Str;
use App\Http\Controllers\ImageController;

class PeriodicalController extends Controller
{
    // const URL = 'http://26.68.32.39:8000'; 
    const URL = 'http://127.0.0.1:8000';

    public function getPeriodicals() {
        $periodicals = Material::where([['material_type', 1], ['periodical_type', 0]])
        ->orderByDesc('updated_at')
        ->get(['accession', 'title', 'author', 'publisher', 'copyright']);

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }
        
        return $periodicals;
    }

    public function getByType($type) {
        $periodicals = Material::where([['material_type', 1], ['periodical_type', $type]])
        ->orderByDesc('updated_at')
        ->get(['accession', 'title', 'authors', 'publisher', 'copyright']);

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL . Storage::url($periodical->image_url);

            $periodical->authors = json_decode($periodical->authors);
        }
        
        return $periodicals;
    }

    public function getPeriodical($id) {
        $periodical = Material::findOrFail($id);
        $periodical->authors = json_decode($periodical->authors);
        if($periodical->image_url)
            $periodical->image_url = self::URL .  Storage::url($periodical->image_url);

        return $periodical;
    }

    /* PROCESSING OF DATA */
    
    public function add(Request $request) {
        
        $request->validate([
            'accession' => 'required|string|max:255',
            'periodical_type' => 'required|integer|max:15',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:155',
            'issue' => 'required|string|max:100',
            'language' => 'required|string|max:15',
            'acquired_date' => 'required|date',
            'date_published' => 'required|date',
            'copyright' => 'required|integer|min:1900|max:'.date('Y'),
            'publisher' =>'required|string|max:255',
            'volume' => 'required|',
            'remarks' => 'nullable|string|max:512',
            'pages' => 'required|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = new Material();
        $model->material_type = 1;

        $model->fill($request->except('image_url', 'authors'));

        if(!empty($request->image_url)) {
            $ext = $request->file('image_url')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            /// Store image and save path
            if($request->image_url != null) {
                $ext = $request->file('image_url')->extension();

                // Check file extension and raise error
                if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                    return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
                }

                // Store image and save path
                $path = $request->file('image_url')->store('public/images/periodicals');

                $model->image_url = $path;
            } 
        }

        $model->title = Str::title($request->title);
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        $model->authors = json_encode($authors);
        
        $model->save();

        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'periodical_type' => 'nullable|integer|max:10',
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:155',
            'issue' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:15',
            'acquired_date' => 'nullable|date',
            'date_published' => 'nullable|date',
            'copyright' => 'nullable|integer|min:1900|max:'.date('Y'),
            'publisher' =>'nullable|string|max:255',
            'volume' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:512',
            'pages' => 'nullable|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = Material::findOrFail($id);

        $model->fill($request->except('image_url'));

        if(!empty($request->image_url)) {
            $ext = $request->file('image_url')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $materials = Material::withTrashed()->where('image_url', '=', $model->image_url)->count();

                if(!empty($model->image_url) && $materials == 1) {
                    
                    $image = new ImageController();
                    $image->delete($model->image_url);
                }
                
                $path = $request->file('image_url')->store('public/images/periodicals');
                $model->image_url = $path;

            } catch (Exception $e) {
                // add function
            }
        }

        $model->save();

        return response()->json($model, 200);
    }

    public function delete(Request $request, $id) {
        $model = Material::findOrFail($id);
        $materials = Material::withTrashed()->where('image_url', '=', $model->image_url)->count();

        if(!empty($model->image_url) && $materials == 1) {
            
            $image = new ImageController();
            $image->delete($model->image_url);
        }
        $model->delete();

        $type = strtolower($model->material_type);
        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, $type, null);

        return response()->json(['Response' => 'Record Archived'], 200);
    }
}

