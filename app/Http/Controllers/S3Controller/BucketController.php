<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Models\Bucket;
use App\Services\S3BucketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BucketController extends Controller
{
    protected S3BucketService $s3BucketService;

    public function __construct(S3BucketService $s3BucketService)
    {
        $this->s3BucketService = $s3BucketService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user(); // Assuming you have the Auth facade imported
        $validator = Validator::make($request->all(), [
            'bucketName' => 'string',
        ]);

        $bucketName = $request->bucketName;
        $bucket = Bucket::getBucketFromName($bucketName);
        $bucketStats = $this->s3BucketService->generateBucketStats($bucket);

        return response()->json($bucketStats);
    }
}
