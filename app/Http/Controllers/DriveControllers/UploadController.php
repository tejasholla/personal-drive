<?php

namespace App\Http\Controllers\DriveControllers;

use App\Helpers\UploadFileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriveController\CreateFolderRequest;
use App\Http\Requests\DriveController\UploadRequest;
use App\Services\LocalFileStatsService;
use App\Services\LPathService;
use App\Traits\FlashMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use FlashMessages;

    protected LPathService $lPathService;
    protected LocalFileStatsService $localFileStatsService;

    public function __construct(
        LPathService $lPathService,
        LocalFileStatsService $localFileStatsService
    ) {
        $this->localFileStatsService = $localFileStatsService;
        $this->lPathService = $lPathService;
    }

    public function store(UploadRequest $request): RedirectResponse
    {
        $files = $request->validated('files');
        $publicPath = $request->validated('path') ?? '';
        $privatePath = $this->lPathService->genPrivatePathWithPublic($publicPath);

        if (!$files) {
            return $this->error('File upload failed. No files uploaded');
        }
        if (!$privatePath) {
            return $this->error('File upload failed. Could not find storage path');
        }

        $successWriteNum = 0;
        foreach ($files as $index => $file) {
            $fileNameWithDir = UploadFileHelper::getUploadedFileFullPath($index);
            $directory = dirname($privatePath . $fileNameWithDir);
            if (!file_exists($directory)) {
                UploadFileHelper::makeFolder($directory);
            }
            if ($file->getContent() && File::put($privatePath . $fileNameWithDir, $file->getContent())) {
                $successWriteNum++;
            }
        }
        if ($successWriteNum > 0) {
            $this->localFileStatsService->generateStats($publicPath);
            return $this->success('Files uploaded: ' . $successWriteNum . ' out of ' . count($files));
        }
        return $this->error('Some/All Files upload failed');
    }


    public function createFolder(CreateFolderRequest $request): RedirectResponse
    {
        $publicPath = $request->validated('path') ?? '';
        $folderName = $request->validated('folderName');
        $privatePath = $this->lPathService->genPrivatePathWithPublic($publicPath);
        $makeFolderRes = UploadFileHelper::makeFolder($privatePath . $folderName);
        if (!$makeFolderRes) {
            return $this->error('Create folder failed');
        }
        $this->localFileStatsService->addFolderPathStat($folderName, $publicPath);
        return $this->success('Created folder successfully');
    }
}
