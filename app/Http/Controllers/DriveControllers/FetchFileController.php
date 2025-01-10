<?php

namespace App\Http\Controllers\DriveControllers;

use App\Exceptions\PersonalDriveExceptions\FetchFileException;
use App\Helpers\EncryptHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriveRequests\FetchFileRequest;
use App\Models\LocalFile;
use App\Services\LocalFileStatsService;
use App\Services\ThumbnailService;
use App\Traits\FlashMessages;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Iman\Streamer\VideoStreamer;

class FetchFileController extends Controller
{
    use FlashMessages;

    protected LocalFileStatsService $localFileStatsService;
    private ThumbnailService $thumbnailService;


    public function __construct(
        LocalFileStatsService $localFileStatsService,
        ThumbnailService $thumbnailService
    ) {
        $this->localFileStatsService = $localFileStatsService;
        $this->thumbnailService = $thumbnailService;
    }

    /**
     * @throws FetchFileException
     */
    public function index(FetchFileRequest $request): void
    {
        $file =  $this->handleHashRequest($request);
        $filePrivatePathName = $file->getPrivatePathNameForFile();
        VideoStreamer::streamFile($filePrivatePathName);
    }

    /**
     * @throws FetchFileException
     */
    public function getThumb(FetchFileRequest $request): void
    {
        $file = $this->handleHashRequest($request);
        if (!$file->has_thumbnail) {
            throw FetchFileException::notFoundStream();
        }
        $filePrivatePathName = $this->thumbnailService->getFullFileThumbnailPath($file);
        VideoStreamer::streamFile($filePrivatePathName);
    }

    /**
     * @throws FetchFileException
     */
    private function handleHashRequest(FetchFileRequest $request): LocalFile
    {
        $fileId = $request->validated('id');

        // Find the file record by ID
        $file = LocalFile::find($fileId);
        if (!$file || !$file->file_type) {
            throw FetchFileException::notFoundStream();
        }

        return $file;
    }
}
