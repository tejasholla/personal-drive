<?php

namespace App\Http\Controllers\S3Controller;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\LocalFile;
use App\Services\LocalFileStatsService;
use App\Services\ThumbnailService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Iman\Streamer\VideoStreamer;

class FetchFileController extends Controller
{
    protected LocalFileStatsService $localFileStatsService;
    private ThumbnailService $thumbnailService;


    public function __construct(
        LocalFileStatsService $localFileStatsService,
        ThumbnailService $thumbnailService
    ) {
        $this->localFileStatsService = $localFileStatsService;
        $this->thumbnailService = $thumbnailService;

    }

    public function index(Request $request, $encryptedId)
    {
        try {
            // Decrypt the ID
            $fileId = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            return ResponseHelper::json('Invalid or tampered hash', false);
        }

        // Find the file record by ID
        $fileRecord = LocalFile::find($fileId);
        if (!$fileRecord ||  !$fileRecord->file_type) {
            return ResponseHelper::json('Could not find file to stream', false);
        }
        $filePrivatePathName = $fileRecord->getPrivatePathNameForFile();
        VideoStreamer::streamFile($filePrivatePathName);
    }
    public function getThumb(Request $request, $encryptedId)
    {
        try {
            // Decrypt the ID
            $fileId = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            return ResponseHelper::json('Invalid or tampered hash', false);
        }

        // Find the file record by ID
        $file = LocalFile::find($fileId);
        if (!$file ||  !$file->file_type || !$file->has_thumbnail) {
            return ResponseHelper::json('Could not thumb', false);
        }

        $filePrivatePathName = $this->thumbnailService->getFullFileThumbnailPath($file);
        VideoStreamer::streamFile($filePrivatePathName);
    }
}
