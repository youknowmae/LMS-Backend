<?php

namespace App\Http\Controllers;

use App\Models\CatalogingLog;
use Illuminate\Http\Request;
use App\Models\AcademicProject;
use App\Models\FilterCategory;

class CatalogingLogController extends Controller
{
    public function get() {
        return CatalogingLog::with('user')->orderByDesc('create_date')->get();
    }

    public function add(int $id, string $action, string $title, string $type, ?string $location) {
        if(in_array($type, ['book', 'article'])) {
            if($action == 'Added' && $type != 'article')
                $pre = 'to';
            else if ($action == 'Added' && $type == 'article')
                $pre = 'of type';
            else
                $pre = 'from';
            $log = $action . ' \'' . $title . '\' ' . $type . ' ' . $pre . ' ' . $location;
        } else if (in_array($type, ['thesis', 'feasibility study', 'capstone', 'research'])){
            $log = $action . ' \'' . $title . '\'' . $type . ' of program ' . $location;
        } else {
            $log = "$action '$title' $type";
        }

        $model = CatalogingLog::create([
            'user_id' => $id,
            'action' => $action,
            'log' => $log
        ]);

        $model->save();
    }
}
