<?php

namespace App\Services;

use App\Models\LocalFile;
use App\Models\Setting;

class LPathService
{
    protected UUIDService $uuidService;

    public function __construct(UUIDService $uuidService)
    {
        $this->uuidService = $uuidService;
    }

    public function getStorageDirPath(): string
    {
        $storagePath = Setting::getSettingByKeyName(Setting::$storagePath);
        $uuid = $this->uuidService->getStorageFilesUUID();
        if (!$storagePath || !$uuid) {
            return '';
        }
        return $storagePath . DIRECTORY_SEPARATOR . $uuid;
    }
    public function getThumbnailDirPath(): string
    {
        $storagePath = Setting::getSettingByKeyName(Setting::$storagePath);
        $uuid = $this->uuidService->getThumbnailsUUID();
        if (!$storagePath || !$uuid) {
            return '';
        }
        return $storagePath . DIRECTORY_SEPARATOR . $uuid;
    }

    public function genPrivatePathWithPublic(string $publicPath = ''): string
    {
        $privateRoot = $this->getStorageDirPath();
        if (!$privateRoot) {
            return '';
        }

        if ($publicPath === '') {
            return $privateRoot . DIRECTORY_SEPARATOR;
        }
        $privatePath = $privateRoot . DIRECTORY_SEPARATOR . $publicPath . DIRECTORY_SEPARATOR;

        if (file_exists($privatePath)) {
            return $privatePath;
        }
        return '';
    }


}
