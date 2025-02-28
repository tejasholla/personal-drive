<?php

namespace App\Http\Controllers\DriveControllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DriveRequests\GetThumbnailRequest;
use App\Services\ThumbnailService;
use App\Traits\FlashMessages;

class ThumbnailController
{
    use FlashMessages;

    private ThumbnailService $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }

    public function update(GetThumbnailRequest $request): JsonResponse
    {
        $fileIds = $request->validated('ids');

        if (! $fileIds) {
            return ResponseHelper::json('Could not generate thumbnails', false);
        }

        $thumbsGenerated = $this->thumbnailService->genThumbnailsForFileIds($fileIds);

        if ($thumbsGenerated === 0) {
            return ResponseHelper::json('No thumbnails generated. No valid files found', false);
        }

        return ResponseHelper::json('');

    }
}
