<?php

namespace App\Http\Controllers\S3Controller;

use App\Helpers\DownloadHelper;
use App\Models\LocalFile;
use App\Services\LocalFileStatsService;
use App\Services\LPathService;
use App\Services\S3BucketService;
use App\Services\S3DownloadService;
use App\Services\S3FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        $fileList = $request->fileList;
        $fileKeyArray = [];
        if ($fileList) {
            $fileKeyArray = json_decode($fileList, true);
        }
        $localFiles = LocalFile::getByIds(array_keys($fileKeyArray));
        if (count($localFiles) === 1) {
            $downloadFilePath = $localFiles[0]->getPrivatePathNameForFile();
        } else {
            $downloadFilePath = $this->pathService->getRootPath() . DIRECTORY_SEPARATOR . Str::uuid() . '.zip';
            $filesArray = [];
            foreach ($localFiles as $localFile) {
                $filesArray[] = $localFile->getPrivatePathNameForFile();
            }
            DownloadHelper::createZipArchive(
                $localFiles,
                $downloadFilePath
            );
        }

        return Response::download($downloadFilePath);
    }
}
