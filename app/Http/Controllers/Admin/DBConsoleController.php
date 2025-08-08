<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBConsoleController extends Controller
{
    public function index()
    {
        return view('admin.db-console', ['output' => null, 'query' => '']);
    }

    public function run(Request $request)
    {
        $query = $request->input('query');
        try {
            if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0) {
                $output = DB::select($query);
            } else {
                $output = DB::statement($query);
            }
        } catch (\Throwable $e) {
            $output = $e->getMessage();
        }

        return view('admin.db-console', ['output' => $output, 'query' => $query]);
    }
}
