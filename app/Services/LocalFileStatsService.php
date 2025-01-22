<?php

namespace App\Services;

use App\Exceptions\PersonalDriveExceptions\UploadFileException;
use App\Models\LocalFile;
use FilesystemIterator;
use Illuminate\Support\Facades\Auth;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class LocalFileStatsService
{
    private LPathService $pathService;

    public function __construct(LPathService $pathService)
    {
        $this->pathService = $pathService;
    }

    public function addFolderPathStat(string $folderName, string $publicPath): void
    {
        $itemPrivatePathname = $this->pathService->genPrivatePathWithPublic($publicPath);
        try {
            LocalFile::create([
                'filename' => $folderName,
                'is_dir' => 1,
                'public_path' => $publicPath,
                'private_path' => $itemPrivatePathname,
                'size' => '',
                'user_id' => Auth::user()?->id ?? 1,
            ]);
        } catch (\Exception $e) {
            throw  UploadFileException::nonewdir();
        }
    }

    public function generateStats(string $path = ''): int
    {
        $rootPathLen = strlen($this->pathService->getStorageDirPath()) + 1;
        $privatePath = $this->pathService->genPrivatePathWithPublic($path);
        if (!$privatePath || !$rootPathLen) {
            return false;
        }
        return $this->populateLocalFileWithStats($privatePath, $rootPathLen);
    }

    private function populateLocalFileWithStats(string $privatePath, int $rootPathLen): int
    {
        $insertArr = [];
        $dirSizes = [];
        $iterator = $this->createFileIterator($privatePath);
        $filesUpdated = 0 ;
        foreach ($iterator as $item) {
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
                'user_id' => Auth::user()?->id ?? 1, // Set the appropriate user ID
                'file_type' => $this->getFileType($item)
            ];
            // Insert in chunks of 100
            if (count($insertArr) === 100) {
                $filesUpdated += LocalFile::insertRows($insertArr);
                $insertArr = []; // Clear the array for the next chunk
            }
        }
        // Insert remaining items if any
        if (!empty($insertArr)) {
            $filesUpdated +=  LocalFile::insertRows($insertArr);
        }
        return $filesUpdated;
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

    private function getFileType(SplFileInfo $item): string
    {
        if ($item->isDir()) {
            return 'folder';
        }
        $mimeType = mime_content_type($item->getPathname());

        if (str_starts_with($mimeType, 'image/')) {
            $fileType = 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $fileType = 'video';
        } elseif ($mimeType === 'application/pdf') {
            $fileType = 'pdf';
        } else {
            $fileType = $item->getExtension();
        }
        return $fileType;
    }
}
