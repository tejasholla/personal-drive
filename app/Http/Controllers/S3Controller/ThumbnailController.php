<?php

namespace App\Http\Controllers\S3Controller;

use App\Helpers\ResponseHelper;
use App\Services\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ThumbnailController
{
    private ThumbnailService $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }

    public function update(Request $request)
    {
        $fileIds = $this->getFileIds($request);
        if (!$fileIds) {
            return ResponseHelper::json('could not generate thumbnails', false);
        }
        if ($this->thumbnailService->genThumbailsForFileIds($fileIds)){
            return ResponseHelper::json('generated', true);
        }
    }

    private function getFileIds(Request $request): array
    {
        $encryptedIds = $request->hashes;
        $fileIds = array_map(fn($encryptedId) => Crypt::decryptString($encryptedId), $encryptedIds);
        return $fileIds;
    }

}
