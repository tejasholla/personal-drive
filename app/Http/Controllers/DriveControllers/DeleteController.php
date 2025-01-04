<?php

namespace App\Http\Controllers\DriveControllers;

use App\Helpers\ResponseHelper;
use App\Models\LocalFile;
use App\Services\LocalFileStatsService;
use App\Services\LPathService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeleteController
{
    protected LocalFileStatsService $localFileStatsService;
    protected LPathService $pathService;

    public function __construct(
        LocalFileStatsService $localFileStatsService,
        LPathService $pathService
    ) {
        $this->localFileStatsService = $localFileStatsService;
        $this->pathService = $pathService;
    }


    public function deleteFiles(Request $request): JsonResponse
    {
        $fileList = $request->fileList;
        $fileKeyArray = [];
        if ($fileList) {
            $fileKeyArray = json_decode($fileList, true);
        }
        $filesInDB = LocalFile::whereIn('id', array_keys($fileKeyArray));
        $rootPath = $this->pathService->getStorageDirPath();
        if (!$filesInDB->count()) {
            return ResponseHelper::json(' No Files found ');
        }

        $filesDeleted = 0;

        foreach ($filesInDB->get() as $file) {
            $filePath = $file->getPrivatePathNameForFile();

            if ($file->is_dir === 1 && file_exists($filePath) && is_dir($filePath) && strstr($filePath, $rootPath)) {
                File::deleteDirectory($filePath);
            }
            if (!file_exists($filePath)) {
                continue;
            }
            if (unlink($filePath)) {
                $filesDeleted++;
            }
        }

        $response = $filesInDB->delete();

        if (!$response || !$filesDeleted) {
            return ResponseHelper::json(' Could not delete files ');
        }
        $this->localFileStatsService->deleteRows($filesInDB);
        return ResponseHelper::json(' Deleted ' . $filesDeleted . ' files ');
    }
}
