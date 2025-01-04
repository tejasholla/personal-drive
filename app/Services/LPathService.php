<?php

namespace App\Services;

use App\Models\Bucket;
use App\Models\File;
use App\Models\LocalFile;
use App\Models\Setting;
use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class LPathService
{
    public function getPrivatePathForPublicPathFromDb(string $publicPath = ''): string
    {
        $privateRoot = $this->getStorageDirPath();
        if (!$privateRoot) {
            return '';
        }

        if ($publicPath === '') {
            return $privateRoot;
        }
        $privatePath = LocalFile::getPrivatePath($publicPath);
        if (!$privatePath) {
            return '';
        }

        if (file_exists($privatePath)) {
            return $privatePath;
        }
        return '';
    }

    public function getStorageDirPath(): string
    {
        $storagePath = Setting::getSettingByKeyName(Setting::$storagePath);
        $uuid = Setting::getUUIDForStorageFiles();
        if (!$storagePath || !$uuid) {
            return '';
        }
        return $storagePath . DIRECTORY_SEPARATOR . $uuid;
    }
    public function getThumbnailDirPath(): string
    {
        $storagePath = Setting::getSettingByKeyName(Setting::$storagePath);
        $uuid = Setting::getUUIDForThumbnails();
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
