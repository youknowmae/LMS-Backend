<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodical;
use Exception;

class PeriodicalController extends Controller
{
    public function getPeriodicals() {
        $periodicals = Periodical::all()->sortByDesc('created_at');

        $periodical_array = [];
        foreach($periodicals as $periodical){
            array_push($periodical_array, $periodical);
        }
        
        return $periodical_array;
    }

    public function getByType($type) {
        $periodicals = Periodical::where('material_type', $type)->get()->sortByDesc('created_at');

        $periodical_array = [];
        foreach($periodicals as $periodical){
            array_push($periodical_array, $periodical);
        }
        
        return $periodical_array;
    }

    public function getPeriodical($id) {
        return Periodical::find($id);
    }

    public function image($id) {
        $material = Periodical::find($id);

        // check if it has no image
        if($material->image_location == null)
            return response()->json(['Response' => 'No Image Found'], 200);

        $image = 'app/' . $material->image_location;
        $path = storage_path($image);
        try {
            return response()->file($path);
        } catch (Exception $e) {
            return response()->json(['Status' => 'File not found'], 404);
        }
    }
    
    public function add(Request $request) {
        // Validate image
        $request->validate([
            'image_location' => 'required|image|mimes:jpeg,png,jpg|max:4096', // Adjust the max size as needed
        ]);

        $model = new Periodical();
        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.'], 400);
        }

        if(!empty($request->image_location)) {
            $ext = $request->file('image_location')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $temp = $model->image_location;
                $path = $request->file('image_location')->store('images/periodicals');
                $model->image_location = $path;

                if(!empty($temp)) {
                    $image = new ImageController();
                    $image->delete($temp);
                }
            } catch (Exception $e) {
                // add function
            }
        }

        $model->save();
        
        $type = strtolower($model->material_type);
        $log = new CatalogingLogController();
        $log->add('Added', $model->title, $type, null);

        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        // return response($request);
        $model = Periodical::findOrFail($id);
        // return response($model);

        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
        }

        if(!empty($request->image_location)) {
            $ext = $request->file('image_location')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            $path = $request->file('image_location')->store('images', 'public');

            $model->image_location = $path;
        }

        $model->save();

        $type = strtolower($model->material_type);
        $log = new CatalogingLogController();
        $log->add('Updated', $model->title, $type, null);

        return response()->json($model, 200);
    }

    public function delete($id) {
        $model = Periodical::findOrFail($id);
        $model->delete();

        $type = strtolower($model->material_type);
        $log = new CatalogingLogController();
        $log->add('Archived', $model->title, $type, null);

        return response()->json(['Response' => 'Record Archived'], 200);
    }
}
