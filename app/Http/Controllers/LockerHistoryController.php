<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LockerHistory;

class LockerHistoryController extends Controller
{
    public function getLogs() {
        return LockerHistory::with('user')->orderByDesc('created_at')->get();
    }

    public function add(int $id, string $action, string $log) {
        $model = LockerHistory::create([
            'user_id' => $id,
            'action' => $action,
            'log' => $log
        ]);

        $model->save();
    }
}
