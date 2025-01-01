<?php

namespace App\Http\Controllers\S3Controller;

use App\Helpers\DownloadHelper;
use App\Models\LocalFile;
use App\Services\LPathService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class DownloadController
{
    protected LPathService $pathService;

    public function __construct(
        LPathService $pathService
    ) {
        $this->pathService = $pathService;
    }

    public function index(Request $request)
    {
        $fileKeyArray = $request->fileList;

        $localFiles = LocalFile::getByIds(array_keys($fileKeyArray));
        if (count($localFiles) === 1 && $localFiles[0]->is_dir === 0) {
            $downloadFilePath = $localFiles[0]->getPrivatePathNameForFile();
        } else {
            $downloadFilePath = $this->pathService->getRootPath() . DIRECTORY_SEPARATOR . Str::uuid() . '.zip';
            DownloadHelper::createZipArchive(
                $localFiles,
                $downloadFilePath
            );
        }

        return Response::download(
            $downloadFilePath,
            basename($downloadFilePath),
            [
                'Content-Disposition' => 'attachment; filename="' . basename($downloadFilePath) . '"'
            ]
        );
    }
}
