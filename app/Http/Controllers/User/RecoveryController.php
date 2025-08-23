<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticCase;
use Illuminate\Support\Facades\Redirect;

class RecoveryController extends Controller
{
    private $columnsConfig = [
        'cases' => [
            'title' => 'Title',
            'deleted_at' => 'Deleted On',
        ],
    ];

    public function index($resource)
    {
        switch ($resource) {
            case 'cases':
                $items = DiagnosticCase::onlyTrashed()->get();
                break;
            default:
                return Redirect::back()->with('notify_error', 'Resource not found.');
        }
        $primaryColumn = isset($primaryColumn) ? $primaryColumn : null;
        $columns = $this->columnsConfig[$resource] ?? [];
        $data = compact('items', 'resource', 'columns', 'primaryColumn');

        return view('user.recovery-management.list')->with('title', 'Recovery')->with($data);
    }
}
