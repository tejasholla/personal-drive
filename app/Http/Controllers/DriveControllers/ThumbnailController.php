<?php

namespace App\Http\Controllers\DriveControllers;

use App\Helpers\EncryptHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\DriveController\GetThumbnailRequest;
use App\Services\ThumbnailService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;

class ThumbnailController
{
    use FlashMessages;

    private ThumbnailService $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }

    public function update(GetThumbnailRequest $request)
    {
        $encryptedIds = $request->validated('hashes');
        $fileIds = $this->getFileIds($encryptedIds);
        if (!$fileIds) {
            return $this->error('Could not generate thumbnails');
        }
        $thumbsGenerated = $this->thumbnailService->genThumbnailsForFileIds($fileIds);
        if ($thumbsGenerated === 0) {
            return $this->error('No thumbnails generated. No valid files found');
        }
    }

    private function getFileIds(array $encryptedIds): array
    {
        return array_map(fn($encryptedId) => EncryptHelper::decrypt($encryptedId), $encryptedIds);
    }
}
