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
        $privateRoot = $this->getRootPath();
        if (!$privateRoot) {
            return '';
        }

        if ($publicPath === '') {
            return $privateRoot;
        }
        $privatePath = LocalFile::getPublicPath($publicPath);
        if (!$privatePath) {
            return '';
        }

        if (file_exists($privatePath)) {
            return $privatePath;
        }
        return '';
    }

    public function getRootPath(): string
    {
        $storagePath = Setting::getSettingByKeyName('storage_path');
        $uuid = Setting::getSettingByKeyName('uuid');
        if (!$storagePath || !$uuid) {
            return '';
        }
        return $storagePath . DIRECTORY_SEPARATOR . $uuid;
    }

    public function genPrivatePathWithPublic(string $publicPath = ''): string
    {
        $privateRoot = $this->getRootPath();
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
