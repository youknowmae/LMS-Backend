<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodical;
use Exception;
use Storage, Str;

class PeriodicalController extends Controller
{
    const URL = 'http://192.168.10.122:8000';
    public function getPeriodicals() {
        $periodicals = Periodical::orderByDesc('updated_at')->get();

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }
        
        return $periodicals;
    }

    public function getByType($type) {
        $periodicals = Periodical::where('material_type', $type)->orderByDesc('updated_at')->get();

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);

            $periodical->authors = json_decode($periodical->authors);
        }
        
        return $periodicals;
    }

    public function getPeriodical($id) {
        return Periodical::find($id);
    }

    // FOR STUDENT PORTAL
    public function viewPeriodicals() {
        $periodicals = Periodical::
        select(['title', 'authors', 'material_type', 'image_url', 'language', 'volume', 'issue', 'copyright'])
        ->orderByDesc('updated_at')->get();

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);

            $periodical->authors = json_decode($periodical->authors);
        }
        
        return $periodicals;
    }

    /* PROCESSING OF DATA */
    
    public function add(Request $request) {
        
        $request->validate([
            'accession' => 'required|string|max:255',
            'material_type' => 'required|string|max:15',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:155',
            'issue' => 'required|integer',
            'language' => 'required|string|max:15',
            'receive_date' => 'required|date',
            'date_published' => 'required|date',
            'copyright' => 'required|integer|min:1900|max:'.date('Y'),
            'publisher' =>'required|string|max:255',
            'volume' => 'required|integer',
            'remarks' => 'nullable|string|max:512',
            'pages' => 'required|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = new Periodical();

        $model->fill($request->except('image_url', 'author'));

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
        
        $type = strtolower($model->material_type);
        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Added', $model->title, $type, null);

        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'material_type' => 'nullable|string|max:15',
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:155',
            'issue' => 'nullable|integer',
            'language' => 'nullable|string|max:15',
            'receive_date' => 'nullable|date',
            'date_published' => 'nullable|date',
            'copyright' => 'nullable|integer|min:1900|max:'.date('Y'),
            'publisher' =>'nullable|string|max:255',
            'volume' => 'nullable|integer',
            'remarks' => 'nullable|string|max:512',
            'pages' => 'nullable|integer',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $model = Periodical::findOrFail($id);

        $model->fill($request->except('image_url'));

        if(!empty($request->image_url)) {
            $ext = $request->file('image_url')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $materials = Periodical::withTrashed()->where('image_url', '=', $model->image_url)->count();

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

        $type = strtolower($model->material_type);
        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Updated', $model->title, $type, null);

        return response()->json($model, 200);
    }

    public function delete(Request $request, $id) {
        $model = Periodical::findOrFail($id);
        $materials = Periodical::withTrashed()->where('image_url', '=', $model->image_url)->count();

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

    //opac
    public function opacGetPeriodicals(Request $request, $material_type){
        if (!in_array($material_type, ['journal', 'magazine', 'newspaper'])) {
            return response()->json(['error' => 'Page not found'], 404);
        }
        
        $sort = $request->input('sort', 'date_published desc');

        $sort = $this->validateSort($sort);

        $periodicals = Periodical::select('id', 'title', 'date_published', 'authors', 'image_url')
                                    ->where('material_type', $material_type)
                                    ->orderBy($sort[0], $sort[1])
                                    ->paginate(24);
        
        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }

        return $periodicals;
    }

    public function opacSearchPeriodicals(Request $request, $material_type){
        if (!in_array($material_type, ['journal', 'magazine', 'newspaper'])) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        $search = $request->input('search');
        $sort = $request->input('sort', 'date_published desc');

        $sort = $this->validateSort($sort);

        $periodicals = Periodical::select('id', 'title', 'date_published', 'authors', 'image_url', 'material_type')
                        ->where('material_type', $material_type)
                        ->where(function ($query) use ($search) {
                            $query->where('title', 'like', '%' . $search . "%")
                                ->orWhere('authors', 'like', '%' . $search . "%");
                        })
                        ->orderBy($sort[0], $sort[1])
                        ->paginate(24);

        if ($periodicals->isEmpty()) {
            return $periodicals;
        }

        foreach($periodicals as $periodical) {
            if($periodical->image_url != null)
                $periodical->image_url = self::URL .  Storage::url($periodical->image_url);
            
            $periodical->authors = json_decode($periodical->authors);
        }

        return $periodicals;
    }
}

