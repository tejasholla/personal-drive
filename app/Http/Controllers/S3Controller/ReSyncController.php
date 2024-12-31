<?php

namespace App\Http\Controllers\S3Controller;

use App\Http\Controllers\Controller;
use App\Services\LocalFileStatsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ReSyncController extends Controller
{
    protected LocalFileStatsService $localFileStatsService;

    public function __construct(
        LocalFileStatsService $localFileStatsService
    ) {
        $this->localFileStatsService = $localFileStatsService;
    }

    public function index(Request $request): void
    {
        $redirectPath = (string) $request->redirect;
        try {
            $this->localFileStatsService->generateStats();
            Log::info('ReSync succ | ');
            session()->flash('status');
            session()->flash('message', 'Sync successful');
            redirect('/drive/' . $redirectPath);
        } catch (\Exception $e) {
            Log::info('ReSync failed | ' . $e->getMessage());
            session()->flash('message', 'ReSync failed. check logs');
            session()->flash('status', false);
            redirect('/drive/' . $redirectPath);
        }
    }
}
