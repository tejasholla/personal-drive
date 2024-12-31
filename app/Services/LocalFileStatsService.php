<?php

namespace App\Services;

use App\Models\Bucket;
use App\Models\File;
use App\Models\LocalFile;
use App\Models\Setting;
use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Exception;
use FilesystemIterator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LocalFileStatsService
{
    private LPathService $pathService;

    public function __construct(LPathService $pathService)
    {
        $this->pathService = $pathService;
    }


    public function generateStats(string $path = ''): bool
    {
        $rootPathLen = strlen($this->pathService->getRootPath()) + 1;
        $privatePath = $this->pathService->genPrivatePathWithPublic($path);
        if (!$privatePath) {
            return false;
        }
        LocalFile::clearTable();

        $this->populateLocalFileWithStats($privatePath, $rootPathLen);

        return true;
    }

    private function populateLocalFileWithStats(string $privatePath, int $rootPathLen): void
    {
        $insertArr = [];
        $folderSizes = [];
        $iterator = $this->createFileIterator($privatePath);
        foreach ($iterator as $item) {
            $itemPrivatePathname = $item->getPath();
            $currentDir = dirname($item->getPathname());
            if (!$item->isDir()) {
                $folderSizes[$currentDir] = array_key_exists(
                    $currentDir,
                    $folderSizes
                ) ? $folderSizes[$currentDir] + $item->getSize() : $item->getSize();
            } else {
                $folderSizes[$currentDir] = array_key_exists(
                    $currentDir,
                    $folderSizes
                ) ? $folderSizes[$currentDir] + $folderSizes[$item->getPathname()] : $folderSizes[$item->getPathname()];
            }
            $publicPathname = substr($itemPrivatePathname, $rootPathLen);
            $insertArr[] = [
                'filename' => $item->getFilename(),
                'is_dir' => $item->isDir(),
                'public_path' => $publicPathname,
                'private_path' => $itemPrivatePathname,
                'size' => $item->isDir() ? $folderSizes[$item->getPathname()] ?? '' : $item->getSize(),
                'user_id' => Auth::user()->id, // Set the appropriate user ID
            ];
            // Insert in chunks of 100
            if (count($insertArr) === 100) {
                LocalFile::insert($insertArr);
                $insertArr = []; // Clear the array for the next chunk
            }
        }
        // Insert remaining items if any
        if (!empty($insertArr)) {
            LocalFile::insert($insertArr);
        }
    }

    private function createFileIterator(string $path): RecursiveIteratorIterator
    {
        $directoryIterator = new RecursiveDirectoryIterator(
            $path,
            FilesystemIterator::SKIP_DOTS
        );

        return new RecursiveIteratorIterator(
            $directoryIterator,
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }

    public function addFolderPathStat($folderName, $publicPath)
    {
        $itemPrivatePathname = $this->pathService->genPrivatePathWithPublic($publicPath);
        return LocalFile::insert([
            'filename' => $folderName,
            'is_dir' => 1,
            'public_path' => $publicPath,
            'private_path' => $itemPrivatePathname,
            'size' => '',
            'user_id' => Auth::user()->id, // Set the appropriate user ID
        ]);
    }
}
