<?php

namespace App\Http\Controllers\S3Controller;

use App\Helpers\UploadFileHelper;
use App\Http\Controllers\Controller;
use App\Services\FileValidationService;
use App\Services\LocalFileStatsService;
use App\Services\LocalFolderService;
use App\Services\LPathService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    protected LocalFolderService $localFolderService;
    protected LPathService $lPathService;
    protected LocalFileStatsService $localFileStatsService;

    public function __construct(
        LocalFolderService $localFolderService,
        LPathService $lPathService,
        LocalFileStatsService $localFileStatsService
    ) {
        $this->localFolderService = $localFolderService;
        $this->localFileStatsService = $localFileStatsService;
        $this->lPathService = $lPathService;
    }

    public function store(Request $request): JsonResponse
    {
        $files = $request->file('files');
        $path = $request->path ?: '';
        $privatePath = $this->lPathService->genPrivatePathWithPublic($path);

        $successWrite = true;

        foreach ($files as $file) {
            $fileNameWithDir = UploadFileHelper::getUploadedFileFullPath($file);
            $directory = dirname($privatePath . $fileNameWithDir);

//            dd($privatePath, $fileNameWithDir, $directory);
            // Create directory if it does not exist
//            dd($directory, file_exists($directory), Storage::makeDirectory($directory));
            if (!file_exists($directory)) {
                $this->localFolderService->makeFolder($directory);
            }

            if (!File::put($privatePath . $fileNameWithDir, $file->getContent())) {
                $successWrite = false;
            }
        }
        if ($successWrite) {
            $this->localFileStatsService->generateStats('');
            return $this->successResponse('Files uploaded successfully');
        }

        Log::info('File upload failed | ');
        return $this->errorResponse('File upload failed. check logs', 400);
    }

    private function successResponse(string $message): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => $message
        ], 200);
    }

    private function errorResponse(string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'message' => $message
        ], $statusCode);
    }

    public function createFolder(Request $request): JsonResponse
    {
        $publicPath = (string) $request->path;
        $folderName = $request->folderName;
        try {
            $privatePath = $this->lPathService->genPrivatePathWithPublic($publicPath);
            $makeFolderRes = $this->localFolderService->makeFolder($privatePath . $folderName);
            $this->localFileStatsService->addFolderPathStat($folderName, $publicPath);
            if ($makeFolderRes) {
                return $this->successResponse('Created folder successfully');
            }
            return $this->errorResponse('Create folder failed. check logs', 400);
        } catch (\Exception $e) {
            Log::info('Create folder failed | ' . $e->getMessage());
            return $this->errorResponse('Create folder failed. check logs', 400);
        }
    }
}
