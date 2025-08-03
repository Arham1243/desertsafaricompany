<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class EnvEditorController extends Controller
{
    public function index()
    {
        $envPath = base_path('.env');
        $lines = file($envPath, FILE_IGNORE_NEW_LINES);
        $env = [];

        foreach ($lines as $line) {
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $env[$key] = $value;
            }
        }

        return view('admin.env-editor', compact('env'));
    }

    public function save(Request $request)
    {
        $keys = $request->input('keys', []);
        $values = $request->input('values', []);

        $newEnv = '';
        foreach ($keys as $i => $key) {
            $value = $values[$i];
            $newEnv .= "$key=$value\n";
        }

        File::put(base_path('.env'), $newEnv);
        Artisan::call('config:clear');

        return redirect()->back()->with('success', '.env updated');
    }
}
