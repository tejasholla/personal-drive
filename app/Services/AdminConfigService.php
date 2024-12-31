<?php

namespace App\Services;

use App\Helpers\UploadFileHelper;
use App\Models\Setting;

class AdminConfigService
{
    public function updateStoragePath(string $storagePathWIthUuid, string $storagePath): array
    {
        if (!UploadFileHelper::makeFolder($storagePathWIthUuid)) {
            return [false, 'Could not make directory. Check permissions'];
        }

        if (Setting::updateSetting('storage_path', $storagePath)) {
            return [false, 'No changes were made'];
        }

        return [true, 'Storage path updated successfully'];
    }

    public function getPhpMaxUploadSize(): false|string
    {
        return ini_get('upload_max_filesize');
    }

    public function getPhpPostMaxUploadSize(): false|string
    {
        return ini_get('post_max_size');
    }
}
