<?php

namespace App\Services;

use App\Models\Bucket;
use App\Models\File;
use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class LocalFolderService
{
    public function createFolder(string $privatePath, mixed $folderName)
    {
    }

    public function makeFolder(string $path, int $permission = 0755): bool
    {
        try {
            if (!file_exists($path)) {
                if (!mkdir($path, $permission, true) && !is_dir($path)) {
                    throw new Exception('Directory "' . $path . '" was not created');
                }
            }
            return true;
        } catch (Exception $e) {
            Log::error('Failed to create directory: ' . $e->getMessage());
            return false;
        }
    }
}
