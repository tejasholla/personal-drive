<?php

namespace App\Http\Controllers\DriveControllers;

use App\Exceptions\PersonalDriveExceptions\FetchFileException;
use App\Http\Requests\DriveController\DownloadRequest;
use App\Models\LocalFile;
use App\Services\DownloadService;
use App\Services\LPathService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController
{
    protected LPathService $pathService;
    protected DownloadService $downloadService;

    public function __construct(
        LPathService $pathService,
        DownloadService $downloadService
    ) {
        $this->pathService = $pathService;
        $this->downloadService = $downloadService;
    }

    public function index(DownloadRequest $request): BinaryFileResponse
    {
        $fileKeyArray = $request->validated('fileList');
        $localFiles = LocalFile::getByIds(array_keys($fileKeyArray))->get();
        if (!$localFiles || count($localFiles) === 0) {
            throw FetchFileException::notFoundDownload();
        }
        $downloadFilePath = $this->downloadService->generateDownloadPath($localFiles);

        return $this->getDownloadResponse($downloadFilePath);
    }

    public function getDownloadResponse(string $downloadFilePath): BinaryFileResponse
    {
        return Response::download(
            $downloadFilePath,
            basename($downloadFilePath),
            [
                'Content-Disposition' => 'attachment; filename="' . basename($downloadFilePath) . '"'
            ]
        );
    }
}
