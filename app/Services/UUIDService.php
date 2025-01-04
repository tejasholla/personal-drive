<?php

namespace App\Services;

use App\Models\Setting;

class UUIDService
{
    private string $storageFilesUUID;
    private string $thumbnailsUUID;

    public function __construct()
    {
        $this->storageFilesUUID = Setting::getSettingByKeyName('uuidForStorageFiles') ?: '';
        $this->thumbnailsUUID = Setting::getSettingByKeyName('uuidForThumbnails') ?: '';

        if (!$this->storageFilesUUID || !$this->thumbnailsUUID) {
            // todo exception
        }
    }

    public function getStorageFilesUUID(): string
    {
        return $this->storageFilesUUID;
    }

    public function getThumbnailsUUID(): string
    {
        return $this->thumbnailsUUID;
    }
}
