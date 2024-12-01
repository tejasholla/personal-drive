<?php

namespace App\Http\Controllers\S3Controller;

use App\Factories\S3AuthenticationServiceFactory;
use App\Http\Controllers\Controller;
use App\Models\Bucket;
use App\Repositories\BucketRepository;
use App\Services\S3BucketService;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class BucketController extends Controller
{
    protected ?S3Client $s3Client;
    protected S3BucketService $s3BucketService;


    public function __construct()
    {
        try {
            $this->s3Client = S3AuthenticationServiceFactory::make();
            $this->s3BucketService = new S3BucketService($this->s3Client, new BucketRepository());
        } catch (\Exception $e) {
            Log::warning("Could not log into s3");
            $this->s3Client = null; // or handle accordingly
        }
    }

    public function index (Request $request): JsonResponse
    {
        $user = Auth::user(); // Assuming you have the Auth facade imported
        $validator = Validator::make($request->all(), [
            'bucketName' => 'string',
        ]);
        if ($this->s3Client === null){
            return response()->json(['error' => 'S3 client is unavailable'], 400);
        }
        
        $bucketName = $request->bucketName;
        $bucket = Bucket::where('s3Name', $bucketName)->first()->toArray();
        $bucketStats = $this->s3BucketService->generateBucketStats($bucket);

        return response()->json($bucketStats);


    }

}
