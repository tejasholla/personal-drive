<?php

namespace App\Http\Controllers\DriveControllers;

use App\Exceptions\PersonalDriveExceptions\FetchFileException;
use App\Http\Requests\DriveRequests\DownloadRequest;
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

    public function index(DownloadRequest $request)
    {
        $fileKeyArray = $request->validated('fileList');
        $localFiles = LocalFile::getByIds($fileKeyArray)->get();
        if (!$localFiles || count($localFiles) === 0) {
            throw FetchFileException::notFoundDownload();
        }
        $downloadFilePath = $this->downloadService->generateDownloadPath($localFiles);
        if (!file_exists($downloadFilePath)) {
            return response()->json([
                'status' => false,
                'message' => "Perhaps trying to download empty dir ? "
            ]);
        }

        return $this->getDownloadResponse($downloadFilePath);
    }

    public function getDownloadResponse(string $downloadFilePath): BinaryFileResponse
    {
        return Response::download(
            $downloadFilePath,
            basename($downloadFilePath),
            [
                'Content-Disposition' => 'attachment; filename="'.basename($downloadFilePath).'"'
            ]
        );
    }

    public function test(): BinaryFileResponse
    {
        return $this->getDownloadResponse('/var/www/html/personal3/3d794827-8ef7-431a-82db-6800c013c3b1/base/GX010037.MP4');
    }
}
