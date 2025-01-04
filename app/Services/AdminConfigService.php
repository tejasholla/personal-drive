<?php

namespace App\Services;

use App\Helpers\UploadFileHelper;
use App\Models\Setting;

class AdminConfigService
{
    private $thumnailDirName = 'thumbnail';

    public function updateStoragePath(string $storagePath, string $uuidStorageFiles, string $uuidThumbnails): array
    {
        if (file_exists($storagePath . DIRECTORY_SEPARATOR . $uuidStorageFiles)) {
            return [false, 'Storage directory already exists'];
        }
        if (!UploadFileHelper::makeFolder($storagePath . DIRECTORY_SEPARATOR . $uuidStorageFiles)) {
            return [false, 'Could not make storage directory. Check permissions'];
        }
        if (!UploadFileHelper::makeFolder($storagePath . DIRECTORY_SEPARATOR . $uuidThumbnails)) {
            return [false, 'Could not make thumbnail directory. Check permissions'];
        }

        if (!Setting::updateSetting('storage_path', $storagePath)) {
            return [false, 'No changes were made'];
        }

        return [true, 'Storage path updated successfully'];
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
