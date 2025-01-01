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
