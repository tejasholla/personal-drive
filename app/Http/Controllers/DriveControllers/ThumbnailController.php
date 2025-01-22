<?php

namespace App\Http\Controllers\DriveControllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DriveRequests\GetThumbnailRequest;
use App\Services\ThumbnailService;
use App\Traits\FlashMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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
        $fileIds = $request->validated('ids');

        if (!$fileIds) {
            Log::info('!$fileIds');
            return ResponseHelper::json('Could not generate thumbnails', false);
        }

        $thumbsGenerated = $this->thumbnailService->genThumbnailsForFileIds($fileIds);
        Log::info('$thumbsGenerated: ' . $thumbsGenerated);

        if ($thumbsGenerated === 0) {
            return ResponseHelper::json('No thumbnails generated. No valid files found', false);
        }

//        return redirect()->back();
    }
}
