<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequests\AdminConfigRequest;
use App\Models\Setting;
use App\Services\AdminConfigService;
use Illuminate\Http\RedirectResponse;
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
        $storagePath = Setting::getSettingByKeyName(Setting::$storagePath);

        return Inertia::render('Admin/Config', [
            'storage_path' => $storagePath,
            'php_max_upload_size' => $this->adminConfigService->getPhpUploadMaxFilesize(),
            'php_post_max_size' => $this->adminConfigService->getPhpPostMaxSize(),
            'php_max_file_uploads' => $this->adminConfigService->getPhpMaxFileUploads(),
        ]);
    }

    public function update(AdminConfigRequest $request): RedirectResponse
    {
        $storagePath = $request->validated('storage_path');
        $updateStoragePathRes = $this->adminConfigService->updateStoragePath($storagePath);
        session()->flash('message', $updateStoragePathRes['message']);
        session()->flash('status', $updateStoragePathRes['status']);
        return redirect()->back();
    }
}
