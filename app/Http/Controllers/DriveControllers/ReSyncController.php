<?php

namespace App\Http\Controllers\DriveControllers;

use App\Helpers\ResponseHelper;
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

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $redirectPath = (string) $request->redirect;
        try {
            $this->localFileStatsService->generateStats();
            Log::info('ReSync succ | ');
            return ResponseHelper::json('Sync successful');

//            session()->flash('status');
//            session()->flash('message', 'Sync successful');
//            redirect('/drive/' . $redirectPath);
        } catch (\Exception $e) {
            Log::info('ReSync failed | ' . $e->getMessage());
            return ResponseHelper::json('ReSync failed. check logs', false);

//            session()->flash('message', 'ReSync failed. check logs');
//            session()->flash('status', false);
//            redirect('/drive/' . $redirectPath);
        }
    }
}
