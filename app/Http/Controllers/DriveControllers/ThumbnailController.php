<?php

namespace App\Http\Controllers\DriveControllers;

use App\Http\Requests\DriveRequests\GetThumbnailRequest;
use App\Services\ThumbnailService;
use App\Traits\FlashMessages;
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
        $publicPath = $request->validated('path') ?? '';
        $publicPath = preg_replace('#^/drive/?#', '', $publicPath);
        if (!$fileIds) {
            session()->flash('message', 'Could not generate thumbnails');
        }
        $thumbsGenerated = $this->thumbnailService->genThumbnailsForFileIds($fileIds);
        if ($thumbsGenerated === 0) {
            session()->flash('message', 'No thumbnails generated. No valid files found');
        }
        return redirect()->route('drive', ['path' => $publicPath]);

        //        return Inertia::render('Drive/DriveHome', [
        //            'token' => csrf_token(),
        //        ]);
    }
}
