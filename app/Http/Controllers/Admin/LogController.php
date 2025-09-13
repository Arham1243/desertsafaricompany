<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    protected $logFile;

    public function __construct()
    {
        $this->logFile = storage_path('logs/laravel.log');
    }

    // Read and return full log
    public function read()
    {
        if (! File::exists($this->logFile)) {
            return response()->json(['message' => 'Log file not found'], 404);
        }

        $content = File::get($this->logFile);

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }

    // Delete log
    public function delete()
    {
        if (File::exists($this->logFile)) {
            File::delete($this->logFile);
        }

        return response()->json(['message' => 'Log file deleted'], 200);
    }
}
