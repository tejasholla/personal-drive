<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SearchFilesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $searchQuery = $request->get('query') ?? '/'; // Retrieve 'path' from the request

        $validator = Validator::make(
            $request->all(),
            [
                'query' => 'string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $files = File::searchFiles($user->id, $searchQuery);
        //        dd($files);
        return response()->json(['files' => $files, 'searchResults' => true], 200);
    }
}
