<?php

namespace App\Http\Controllers\S3Controller;

use App\Services\S3BucketService;
use App\Services\S3DownloadService;
use App\Services\S3FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DownloadController
{
    protected S3BucketService $s3BucketService;
    protected S3DownloadService $s3DownloadService;

    public function __construct(S3DownloadService $s3DownloadService, S3BucketService $s3BucketService)
    {
        $this->s3BucketService = $s3BucketService;
        $this->s3DownloadService = $s3DownloadService;
    }

    public function index(Request $request): JsonResponse
    {
        $fileKeyArray = $request->fileList;
        $bucketName = $request->bucketName;
        $path = $request->path ?: '';

        $signedUrls = [];
        foreach ($fileKeyArray as $fileKey => $isDir) {
            if (!$isDir) {
                $signedUrls[] = $this->s3DownloadService->generatePreSignUrlForFileKey($bucketName, $fileKey);
            }
        }
        return response()->json([
            'ok' => true,
            'urls' => $signedUrls
        ], 200);
    }
}