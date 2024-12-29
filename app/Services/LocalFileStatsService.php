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


    public static function generateStats(string $path): void
    {
        $insertArr = [];
        $lPathService = new LPathService();

        $rootPath = $lPathService->getRootPath();
        $rootPathLen = strlen($rootPath) + 1;
        $privatePath = $lPathService->genPrivatePathWithPublic($path);


        $directoryIterator = new RecursiveDirectoryIterator($privatePath, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);

        if ($privatePath) {
            LocalFile::clearTable();
            foreach ($iterator as $item) {
                $itemPrivatePathname = $item->getPath();
                $publicPathname = substr($itemPrivatePathname, $rootPathLen);
                $insertArr[] = [
                    'filename' => $item->getFilename(),
                    'is_dir' => $item->isDir(),
                    'public_path' => $publicPathname,
                    'private_path' => $itemPrivatePathname,
                    'size' => $item->isDir() ? '' : $item->getSize(),
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
        // insert insertArr into database
    }

    public function addFolderPathStat($folderName, $publicPath)
    {
        $itemPrivatePathname = $this->pathService->getPrivatePathForPublicPathFromDb($publicPath);
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
