<?php

namespace App\Http\Controllers\DriveControllers;

use App\Http\Requests\DriveController\FileDeleteRequest;
use App\Models\LocalFile;
use App\Services\FileDeleteService;
use App\Services\LocalFileStatsService;
use App\Services\LPathService;
use App\Traits\FlashMessages;
use Illuminate\Http\RedirectResponse;

class FileDeleteController
{
    use FlashMessages;

    protected LocalFileStatsService $localFileStatsService;
    protected LPathService $pathService;
    protected FileDeleteService $fileDeleteService;

    public function __construct(
        LocalFileStatsService $localFileStatsService,
        LPathService $pathService,
        FileDeleteService $fileDeleteService
    ) {
        $this->localFileStatsService = $localFileStatsService;
        $this->pathService = $pathService;
        $this->fileDeleteService = $fileDeleteService;
    }


    public function deleteFiles(FileDeleteRequest $request): RedirectResponse
    {
        $fileKeyArray = $request->validated('fileList');
        $rootPath = $this->pathService->getStorageDirPath();
        $localFiles = LocalFile::getByIds($fileKeyArray);
        if (!$localFiles->count()) {
            return $this->error('No valid files in database. Try a ReSync first');
        }

        $filesDeleted = $this->fileDeleteService->deleteFiles($localFiles, $rootPath);

        $response = $localFiles->delete();
        if (!$response || !$filesDeleted) {
            return $this->error('Could not delete files');
        }
        return $this->success('Deleted ' . $filesDeleted . ' files');
    }
}
