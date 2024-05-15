<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class ImageController extends Controller
{
        // Deletes an Image
        public function delete(string $path) {
            // Check if the directory exists and delete it
            if (Storage::exists($path)) {

                Storage::delete($path);
                return response()->json(['Status' => "Image successfully deleted."], 200);
            } else {
                return response()->json(['Status' => "Directory not found."], 404);
            }
        }

    // Deletes all Images
    public function deleteAll(string $type) {
        
        $directory = 'images/';
        if($type == 'books')
            $directory = $directory . 'books';
        else if ($type == 'projects')
            $directory = $directory . 'projects';
        else
            return response()->json(['Error' => 'Type Error: There is no type ' . $type]);        

        // Check if the directory exists and delete it
        if (Storage::exists($directory)) {

            Storage::deleteDirectory($directory);
            return response()->json(['Status' => "All files within the directory deleted successfully."], 200);
        } else {
            return response()->json(['Status' => "Directory not found."], 404);
        }
    }
}
