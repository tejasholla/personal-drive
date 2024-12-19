<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Models\Bucket;
use App\Services\FileValidationService;
use App\Services\S3BucketService;
use App\Services\S3UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    protected S3UploadService $s3UploadService;
    protected S3BucketService $s3BucketService;

    public function __construct(S3UploadService $s3UploadService, S3BucketService $s3BucketService)
    {
        $this->s3UploadService = $s3UploadService;
        $this->s3BucketService = $s3BucketService;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file'
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $files = $request->file('files');

        $bucketName = $request->bucketName;
        $path = $request->path ?: '';

        try {
            foreach ($files as $file) {
                if (!$this->validateFile($file)->isValid()) {
                    return $this->errorResponse('invalid files uploaded', 400);
                }
                // Store file in S3 bucket
                $this->s3UploadService->uploadFile($bucketName, $path, $file);
            }
            $this->s3BucketService->updateBucketStatsFromBucketName($bucketName, $path ?: '/');


            return $this->successResponse('Files uploaded successfully');
        } catch (\Exception $e) {
            Log::info('File upload failed | ' . $e->getMessage());
            return $this->errorResponse('File upload failed. check logs', 400);
        }
    }

    public function createFolder(Request $request): JsonResponse
    {
        $bucketName = $request->bucketName;
        $path = (string)$request->path ;
        $path .= '/';
        $folderName = $request->folderName;
        try {
            $this->s3UploadService->createFolder($bucketName, $path, $folderName);
            $this->s3BucketService->updateBucketStatsFromBucketName($bucketName, $path);
            return $this->successResponse('Files uploaded successfully');
        } catch (\Exception $e) {
            Log::info('Create folder failed | ' . $e->getMessage());
            return $this->errorResponse('Create folder failed. check logs', 400);
        }
    }

    private function errorResponse(string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'message' => $message
        ], $statusCode);
    }

    private function validateFile($file)
    {
        $fileName = $file->getClientOriginalName();
        $validator = new FileValidationService();
        $validator->validate($fileName);
        return $file;
    }

    private function successResponse(string $message): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => $message
        ], 200);
    }

}
