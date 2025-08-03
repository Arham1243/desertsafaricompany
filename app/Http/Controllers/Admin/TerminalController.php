<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TerminalController extends Controller
{
    public function index()
    {
        return view('admin.terminal');
    }

    public function run(Request $request)
    {
        $command = $request->input('command');
        $options = $request->input('options', []);

        try {
            Artisan::call($command, $options);

            return response()->json([
                'success' => true,
                'output' => Artisan::output(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'output' => $e->getMessage(),
            ]);
        }
    }
}
