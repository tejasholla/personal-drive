<?php

namespace App\Services;

use App\Models\Bucket;
use App\Models\File;
use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class S3UploadService
{
    protected S3Client $s3Client;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    /**
     * @throws Exception
     */
    public function uploadFile(string $bucketName, string $path, UploadedFile $file): void
    {
        $key = $this->getFileKey($file, $path);
        $source = fopen($file->getPathname(), 'rb');
        $this->uploadS3Object($bucketName, $key, $source);
    }

    public function getFileKey(UploadedFile $file, string $path): string
    {
        $fileName = $file->getClientOriginalName();
        $fileIndex = array_search($file->getClientOriginalName(), $_FILES['files']['name']);
        if ($fileIndex !== false) {
            $fileName = $_FILES['files']['full_path'][$fileIndex];
        }
        return $path . '/' . $fileName;
    }

    /**
     * @throws Exception
     */
    public function uploadS3Object(string $bucketName, string $key, $source): void
    {
        $uploader = new ObjectUploader(
            $this->s3Client,
            $bucketName,
            $key,
            $source
        );
        try {
            $result = $uploader->upload();
            Log::info('upload S3 failed: ' . json_encode($result["@metadata"]));
            if ($result["@metadata"]["statusCode"] != '200') {
                throw new Exception('Failed to upload file to S3');
            }
        } catch (Exception $e) {
            throw new Exception('Failed to upload file to S3 ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function createFolder(string $bucketName, string $path, string $folderName): void
    {
        $key = $path . '/' . $folderName . '/';
        $source = fopen('php://temp', 'rb');
        $this->uploadS3Object($bucketName, $key, $source);
    }
}
