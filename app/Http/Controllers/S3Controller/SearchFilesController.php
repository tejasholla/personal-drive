<?php

namespace App\Http\Controllers\S3Controller;

use App\Factories\S3AuthenticationServiceFactory;
use App\Factories\S3ServiceFactory;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Repositories\BucketRepository;
use App\Services\S3AuthenticationService;
use App\Services\S3BucketService;
use App\Services\S3Service;
use Aws\CloudWatch\CloudWatchClient;
use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Ec2\Ec2Client;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;


class SearchFilesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $searchQuery = $request->get('query') ?? '/'; // Retrieve 'path' from the request

        $validator = Validator::make($request->all(), [
            'query' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $files = File::searchFiles($user->id,$searchQuery);
//        dd($files);
        return response()->json(['files' => $files], 200);
    }

}
