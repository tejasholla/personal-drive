<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Models\LocalFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SearchFilesController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $user = Auth::user();
        $searchQuery = $request->post('query') ?? '/'; // Retrieve 'path' from the request

        $validator = Validator::make(
            $request->all(),
            [
                'query' => 'string',
            ]
        );

        if ($validator->fails()) {
            return Inertia::render('Aws/FileManager', [
                'files' => [],
                'searchResults' => true
            ]);
        }

        $files = LocalFile::searchFiles($user->id, $searchQuery);

        return Inertia::render('Aws/FileManager', [
            'files' => $files,
            'searchResults' => true
        ]);
    }
}
