<?php

namespace App\Services;

use App\Helpers\FileSizeFormatter;
use App\Models\Bucket;
use App\Models\File;
use Aws\S3\S3Client;

class S3FileService
{
    protected S3Client $s3Client;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    public function getFilesOfPathLocal(string $bucketName, string $path = ''): array
    {
        $bucket = Bucket::where('s3Name', $bucketName)->first();
        if (!$bucket) {
            return [];
        }
        $query = File::where('user_id', auth()->id())
            ->where('bucket_id', $bucket->id);

        if (!$path || $path === '/') {
            $path = '';
        }
        $query->where('path', $path);

        $fileItems = $query->get();
        $fileItems = $fileItems->map(function ($item) {
            if ($item->size) {
                $item->size = FileSizeFormatter::format((int) $item->size);
            }
            return $item;
        });
        return $fileItems->toArray();
    }

    public function deleteFiles(string $bucketName, array $fileKeyArray)
    {
        $fileKeys = [];
        foreach ($fileKeyArray as $fileKey => $isDir) {
            if ($isDir) {
                $folderKeys = [];

                //delete folder from db
                $folderDbDel = File::where('s3key', $fileKey)->delete();
//                dd($folderDbDel, $fileKey);

                // get all child files of folder
                $childFilesQuery = File::where('path', 'like', $fileKey . '%');
                $childFiles = $childFilesQuery->get();
                foreach ($childFiles as $childFile) {
                    $folderKeys[] = ['Key' => $childFile->s3key];
                }

                //delete recursively all files/folders from db
                $childFilesQuery->delete();

                // delete recursively all files/folders from s3
                try {
                    $deletedFolder = $this->s3Client->deleteObjects([
                        'Bucket' => $bucketName,
                        'Delete' => [
                            'Objects' => $folderKeys
                        ]
                    ]);
                } catch (\Exception $e) {
                    dd('failed', $folderKeys);
                    return false;
                }
            } else {
                $fileKeys[] = ['Key' => $fileKey];
            }
        }

        if ($fileKeys) {
            $filesDeleted = $this->s3Client->deleteObjects([
                'Bucket' => $bucketName,
                'Delete' => [
                    'Objects' => $fileKeys
                ]
            ]);
            return $filesDeleted;
        }
    }
}
