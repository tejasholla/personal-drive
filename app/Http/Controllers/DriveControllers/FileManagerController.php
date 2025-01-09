<?php

namespace App\Http\Controllers\DriveControllers;

use App\Http\Controllers\Controller;
use App\Models\LocalFile;
use Inertia\Inertia;
use Inertia\Response;

class FileManagerController extends Controller
{
    public function index(string $path = ''): Response
    {
        $files = LocalFile::getFilesForPublicPath($path);

        return Inertia::render('Drive/DriveHome', [
            'files' => $files,
            'path' => '/drive' . ( $path ? '/' . $path:''),
            'token' => csrf_token(),
        ]);
    }
}
