<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Services\FileValidationService;
use App\Services\LocalFolderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected LocalFolderService $localFolderService;

    public function __construct(LocalFolderService $localFolderService)
    {
        $this->localFolderService = $localFolderService;
    }

    public function store(Request $request): JsonResponse
    {
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
            $this->localFolderService->createFolder($bucketName, $path, $folderName);
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
