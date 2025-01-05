<?php

namespace App\Services;

use App\Helpers\DownloadHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class DownloadService
{
    public function generateDownloadPath(Collection $localFiles): string
    {
        if ($this->isSingleFile($localFiles)) {
            return $localFiles[0]->getPrivatePathNameForFile();
        }

        return $this->createZipFile($localFiles);
    }

    public function isSingleFile(Collection $localFiles): bool
    {
        return count($localFiles) === 1 && !$localFiles[0]->is_dir;
    }

    public function createZipFile(Collection $localFiles): string
    {
        $downloadFilePath = '/tmp' . DIRECTORY_SEPARATOR . Str::random(8) . now()->format('Y_m_d') . '.zip';
        DownloadHelper::createZipArchive($localFiles, $downloadFilePath);
        return $downloadFilePath;
    }

}