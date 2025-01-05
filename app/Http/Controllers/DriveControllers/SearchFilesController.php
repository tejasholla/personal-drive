<?php

namespace App\Http\Controllers\DriveControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriveController\SearchRequest;
use App\Models\LocalFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class SearchFilesController extends Controller
{
    public function index(SearchRequest $request): Response
    {
        $searchQuery = $request->validated('query') ?? '/';

        $files = LocalFile::searchFiles($searchQuery);

        return Inertia::render('Aws/FileManager', [
            'files' => $files,
            'searchResults' => true
        ]);
    }
}
