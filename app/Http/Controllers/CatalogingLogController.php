<?php

namespace App\Http\Controllers;

use App\Models\CatalogingLog;
use Illuminate\Http\Request;

class CatalogingLogController extends Controller
{
    public function get() {
        return CatalogingLog::orderByDesc('create_date')->get();
    }

    public function add(string $action, string $title, string $type, ?string $location) {
        if(in_array($type, ['book', 'thesis', 'dissertation', 'feasibility study', 'article'])) {
            if($action == 'Added' && $type != 'article')
                $pre = 'to';
            else if ($action == 'Added' && $type == 'article')
                $pre = 'of type';
            else
                $pre = 'from';
            $log = $action . ' \'' . $title . '\' ' . $type . ' ' . $pre . ' ' . $location;
        } else {
            $log = $action . ' \'' . $title . '\' ' . $type;
        }

        $model = CatalogingLog::create([
            'action' => $action,
            'log' => $log
        ]);
    }
}
