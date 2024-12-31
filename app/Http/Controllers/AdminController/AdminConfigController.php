<?php

namespace App\Http\Controllers\AdminController;

use App\Helpers\UploadFileHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AdminConfigService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminConfigController extends Controller
{
    protected AdminConfigService $adminConfigService;

    public function __construct(AdminConfigService $adminConfigService)
    {
        $this->adminConfigService = $adminConfigService;
    }

    public function index(): Response
    {
        $settings = Setting::getAllSettings();
        $message = session('message');
        $status = session('status');

        return Inertia::render('Admin/Config', [
            'settings' => $settings,
            'message' => $message,
            'status' => $status,
            'php_max_upload_size' => $this->adminConfigService->getPhpMaxUploadSize(),
            'php_post_max_size' => $this->adminConfigService->getPhpPostMaxUploadSize(),
        ]);
    }

    /**
     * Update settings: storage_path,
     */
    public function update(Request $request): RedirectResponse
    {
        $storagePath = $request->input('storage_path');
        $uuid = Setting::getUUID();
        if (!$uuid) {
            $this->updateResponse('no uuid found ! ', false);
        }

        $updateStoragePathRes = $this->adminConfigService->updateStoragePath(
            $storagePath . DIRECTORY_SEPARATOR . $uuid,
            $request->input('storage_path')
        );
        return $this->updateResponse(json_encode($updateStoragePathRes[1]), $updateStoragePathRes[0]);
    }

    public function updateResponse(string $message, bool $status): RedirectResponse
    {
        return to_route('admin-config')->with([
            'message' => $message,
            'status' => $status
        ]);
    }
}
