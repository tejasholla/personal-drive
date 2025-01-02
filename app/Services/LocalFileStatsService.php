<?php

namespace App\Services;

use App\Models\LocalFile;
use FilesystemIterator;
use Illuminate\Support\Facades\Auth;
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
        $dirSizes = [];
        $iterator = $this->createFileIterator($privatePath);
        foreach ($iterator as $item) {
            $mimeType = mime_content_type($item->getPathname());

            $itemPrivatePathname = $item->getPath();
            $currentDir = dirname($item->getPathname());
            if (!$item->isDir()) {
                $dirSizes[$currentDir] = array_key_exists(
                    $currentDir,
                    $dirSizes
                ) ? $dirSizes[$currentDir] + $item->getSize() : $item->getSize();
            } elseif (array_key_exists($item->getPathname(), $dirSizes)) {
                $dirSizes[$currentDir] = array_key_exists(
                    $currentDir,
                    $dirSizes
                ) ? $dirSizes[$currentDir] + $dirSizes[$item->getPathname()] : $dirSizes[$item->getPathname()];
            }
            $publicPathname = substr($itemPrivatePathname, $rootPathLen);
            $insertArr[] = [
                'filename' => $item->getFilename(),
                'is_dir' => $item->isDir(),
                'public_path' => $publicPathname,
                'private_path' => $itemPrivatePathname,
                'size' => $item->isDir() ? $dirSizes[$item->getPathname()] ?? '' : $item->getSize(),
                'user_id' => Auth::user()->id, // Set the appropriate user ID
                'file_type' => $this->getFileType($mimeType)
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

    private function getFileType(string $mimeType): string
    {
        $fileType = '';
        if (str_starts_with($mimeType, 'image/')) {
            $fileType = 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $fileType = 'video';
        } elseif ($mimeType === 'application/pdf') {
            $fileType = 'pdf';
        }
        return $fileType;
    }
}
