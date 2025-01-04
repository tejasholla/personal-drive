<?php

namespace App\Services;

use App\Helpers\UploadFileHelper;
use App\Models\Setting;

class AdminConfigService
{

    private UUIDService $uuidService;

    public function __construct(UUIDService $uuidService)
    {
        $this->uuidService = $uuidService;
    }

    public function updateStoragePath(string $storagePath): array
    {

        $uuidStorageFiles = $this->uuidService->getStorageFilesUUID();
        $uuidThumbnails = $this->uuidService->getThumbnailsUUID();
        if (file_exists($storagePath . DIRECTORY_SEPARATOR . $uuidStorageFiles)) {
            return ['status' => false, 'message' => 'Storage directory already exists'];
        }
        if (!UploadFileHelper::makeFolder($storagePath . DIRECTORY_SEPARATOR . $uuidStorageFiles)) {
            return ['status' => false, 'message' => 'Could not make storage directory. Check permissions'];
        }
        if (!UploadFileHelper::makeFolder($storagePath . DIRECTORY_SEPARATOR . $uuidThumbnails)) {
            return ['status' => false, 'message' => 'Could not make thumbnail directory. Check permissions'];
        }

        if (!Setting::updateSetting('storage_path', $storagePath)) {
            return ['status' => false, 'message' => 'No changes were made'];
        }

        return ['status' => true, 'message' => 'Storage path updated successfully'];
    }

    public function getPhpUploadMaxFilesize(): false|string
    {
        return ini_get('upload_max_filesize');
    }

    public function getPhpPostMaxSize(): false|string
    {
        return ini_get('post_max_size');
    }

    public function getPhpMaxFileUploads(): false|string
    {
        return ini_get('max_file_uploads');
    }
}
