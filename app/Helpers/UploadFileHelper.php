<?php

namespace App\Helpers;

class UploadFileHelper
{
    public static function getUploadedFileFullPath($fileIndex): string
    {
        return $_FILES['files']['full_path'][$fileIndex];
    }

    public static function makeFolder(string $path, int $permission = 0755): bool
    {
        if (is_dir($path)) {
            return true;
        }

        if (!mkdir($path, $permission, true) && !is_dir($path)) {
            return false;
        }

        return true;
    }
}
