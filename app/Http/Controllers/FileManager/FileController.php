<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class FileController extends Controller
{
    public function index(Request $request): JsonResponse|\Inertia\Response
    {
        // get current loggedin user 
        
        $user = Auth::user(); // Assuming you have the Auth facade imported
        $path = $request->get('path') ?? '/'; // Retrieve 'path' from the request
        

        $validator = Validator::make($request->all(), [
            'path' => 'string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        $files = File::getFilesForUserInPath($user->id,$path);
        return Inertia::render('FileManager/Index', [
            'files' => $files,
        ]);
    }


}
