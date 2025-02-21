<?php

namespace App\Services;

use App\Exceptions\PersonalDriveExceptions\FetchFileException;
use App\Helpers\DownloadHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class DownloadService
{
    /**
     * @throws FetchFileException
     */
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

    /**
     * @throws FetchFileException
     */
    public function createZipFile(Collection $localFiles): string
    {
        $outputZipPath = '/tmp' . DIRECTORY_SEPARATOR . Str::random(8) . now()->format('Y_m_d') . '.zip';
        DownloadHelper::createZipArchive($localFiles, $outputZipPath);
        return $outputZipPath;
    }

}