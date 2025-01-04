<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Models\LocalFile;
use Inertia\Inertia;
use Inertia\Response;

class FileManagerController extends Controller
{
    public function index($path = ''): Response
    {
        $pathFiles = LocalFile::getFilesForPublicPath($path);

        return Inertia::render('Aws/FileManager', [
            'files' => $pathFiles,
            'path' => $path,
            'token' => csrf_token(),
        ]);
    }
}
