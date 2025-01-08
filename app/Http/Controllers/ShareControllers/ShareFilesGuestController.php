<?php

namespace App\Http\Controllers\ShareControllers;

use App\Http\Requests\DriveController\ShareFilesGuestRequest;
use App\Models\Share;
use Inertia\Inertia;
use Inertia\Response;

class ShareFilesGuestController
{
    public function index(ShareFilesGuestRequest $request): Response
    {
        $slug = $request->validated('slug');
        $files = Share::getFilenamesBySlug($slug);

        return Inertia::render('Drive/GuestSharedLayout', [
            'files' => $files,
            'path' => '',
            'token' => csrf_token(),
            'guest' => 'on'
        ]);
    }
}