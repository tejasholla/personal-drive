<?php

namespace App\Http\Controllers\ShareControllers;

use App\Models\Share;
use App\Services\LocalFileStatsService;
use App\Services\LPathService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareFilesAllController
{
    use FlashMessages;

    protected LPathService $pathService;
    protected LocalFileStatsService $localFileStatsService;

    public function __construct(
        LPathService $pathService,
        LocalFileStatsService $localFileStatsService
    ) {
        $this->localFileStatsService = $localFileStatsService;
        $this->pathService = $pathService;
    }

    public function index(Request $request)
    {
        $shares = Share::getAll();
        return Inertia::render('Drive/Shares/AllShares', ['shares' => $shares]);
    }
}
