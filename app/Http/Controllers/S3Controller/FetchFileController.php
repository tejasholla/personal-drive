<?php

namespace App\Http\Controllers\S3Controller;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\LocalFile;
use App\Services\LocalFileStatsService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Iman\Streamer\VideoStreamer;


class FetchFileController extends Controller
{
    protected LocalFileStatsService $localFileStatsService;

    public function __construct(
        LocalFileStatsService $localFileStatsService
    ) {
        $this->localFileStatsService = $localFileStatsService;
    }

    public function index(Request $request, $encryptedId)
    {

        try {
            // Decrypt the ID
            $fileId = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(403, 'Invalid or tampered hash');
        }

        // Find the file record by ID
        $fileRecord = LocalFile::find($fileId);

        $filePrivatePathName = $fileRecord->getPrivatePathNameForFile();
        VideoStreamer::streamFile($filePrivatePathName);

        // Stream the file to the client
//        return new StreamedResponse(function () use ($filePrivatePathName) {
//            $stream = fopen($filePrivatePathName, 'rb');
//            if ($stream === false) {
//                abort(500, 'Unable to open file for reading');
//            }            while (!feof($stream)) {
//                echo fread($stream, 8192); // Send 8KB chunks
//            }
//            fclose($stream);
//        }, 200, [
//            'Content-Type' => Storage::mimeType($filePrivatePathName),
//            'Content-Disposition' => 'inline; filename="' . $fileRecord->filename . '"',
//        ]);
    }
}
