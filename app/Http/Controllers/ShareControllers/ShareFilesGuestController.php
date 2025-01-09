<?php

namespace App\Http\Controllers\ShareControllers;

use App\Exceptions\PersonalDriveExceptions\ShareFileException;
use App\Http\Requests\DriveController\ShareFilesGuestRequest;
use App\Models\LocalFile;
use App\Models\Share;
use Inertia\Inertia;
use Inertia\Response;

class ShareFilesGuestController
{

    public function index(ShareFilesGuestRequest $request): Response
    {
        $slug = $request->validated('slug');
        $path = $request->validated('path');
        $share = Share::whereBySlug($slug)->first();

        if (!$share) {
            throw ShareFileException::couldNotShare();
        }

        if ($path) {
            $files = Share::getFilenamesByPath($share->id, $share->public_path . DIRECTORY_SEPARATOR . $path);
        } else {
            $files = Share::getFilenamesBySlug($slug);
        }
        $files = LocalFile::modifyFileCollectionForGuest($files, $share->public_path);
        return Inertia::render('Drive/ShareFilesGuestHome', [
            'files' => $files,
            'path' => '/shared/' . $slug . ($path ? '/'. $path : '') ,
            'token' => csrf_token(),
            'guest' => 'on',

        ]);
    }
}
