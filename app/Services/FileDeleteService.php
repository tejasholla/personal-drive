<?php

namespace App\Services;

use App\Models\LocalFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;

class FileDeleteService
{
    public function deleteFiles(Builder $filesInDB, string $rootPath): int
    {
        $filesDeleted = 0;

        foreach ($filesInDB->get() as $file) {
            $privateFilePathName = $file->getPrivatePathNameForFile();
            if (!file_exists($privateFilePathName)) {
                continue;
            }

            // Handle directory deletion
            if (
                $this->isDeletableDirectory($file, $privateFilePathName, $rootPath) &&
                $file->deleteFromPublicPath()
            ) {
                File::deleteDirectory($privateFilePathName);
                $filesDeleted++;
            }

            // Handle file deletion
            if ($this->isDeletableFile($file) && unlink($privateFilePathName)) {
                $filesDeleted++;
            }
        }
        return $filesDeleted;
    }

    private function isDeletableDirectory(LocalFile $file, string $privateFilePathName, string $rootPath): bool
    {
        return $file->is_dir === 1 && file_exists($privateFilePathName) && is_dir($privateFilePathName) && strstr(
            $privateFilePathName,
            $rootPath
        );
    }

    private function isDeletableFile(LocalFile $file): bool
    {
        return $file->is_dir === 0;
    }
}
