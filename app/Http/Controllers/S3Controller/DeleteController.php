<?php

namespace App\Http\Controllers\S3Controller;

use App\Services\S3BucketService;
use App\Services\S3FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteController
{
    protected S3BucketService $s3BucketService;
    protected S3FileService $s3FileService;

    public function __construct(S3FileService $s3FileService, S3BucketService $s3BucketService)
    {
        $this->s3BucketService = $s3BucketService;
        $this->s3FileService = $s3FileService;
    }

    public function deleteFiles(Request $request): JsonResponse
    {
        $fileList = $request->fileList;
        $bucketName = $request->bucketName;
        $path = $request->path ?: '';
        $response = $this->s3FileService->deleteFiles($bucketName, $fileList);
        $this->s3BucketService->updateBucketStatsFromBucketName($bucketName, $path);
        return response()->json([
            'ok' => true,
            'message' => "okay"
        ], 200);
    }
}