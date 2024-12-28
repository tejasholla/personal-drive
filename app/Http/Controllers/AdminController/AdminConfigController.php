<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\LocalFolderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminConfigController extends Controller
{
    protected LocalFolderService $localFolderService;

    public function __construct(LocalFolderService $localFolderService)
    {
        $this->localFolderService = $localFolderService;
    }

    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $message = session('message');
        $status = session('status');

        return Inertia::render('Admin/Config', [
            'settings' => $settings, 'message' => $message, 'status' => $status
        ]);
    }

    /**
     * Update settings: storage_path,
     */
    public function update(Request $request): RedirectResponse
    {
        $storagePath = $request->input('storage_path');
        // get check uuid
        $storageUuid = Setting::getSettingByKeyName('uuid');
        if (!$storageUuid) {
            $this->updateResponse('no uuid found ! ', false);
        }

        if (!$this->localFolderService->makeFolder($storagePath . '/' . $storageUuid)) {
            return $this->updateResponse('could not make storage path directory', false);
        }

        $updateResult = Setting::updateSetting('storage_path', $request->input('storage_path'));

        if ($updateResult->wasRecentlyCreated || $updateResult->wasChanged()) {
            return $this->updateResponse('Storage path updated successfully.', true);
        }

        return $this->updateResponse('No changes were made.', false);
    }

    public function updateResponse(string $message, bool $status): RedirectResponse
    {
        return to_route('admin-config')->with([
            'message' => $message,
            'status' => $status
        ]);
    }
}
