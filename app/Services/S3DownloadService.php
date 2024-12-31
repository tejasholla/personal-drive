<?php

namespace App\Services;

use App\Models\Bucket;
use App\Models\File;
use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class S3DownloadService
{
    protected S3Client $s3Client;
    private int $expirationSeconds = 600;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }


    public function downloadFiles(string $bucketName, $files)
    {
        try {
            // Get the object.
            $args = [];
            foreach ($files as $fileKey => $isDir) {
                $args[] = [
                    'Bucket' => $bucketName,
                    'Key' => $fileKey
                ];
            }
            $result = $this->s3Client->getObject($args);

            // Display the object in the browser.
            header("Content-Type: {$result['ContentType']}");
            echo $result['Body'];
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function generatePreSignUrlForFileKey(string $bucketName, string $fileKey): string
    {
        // Similar to the upload example, but for a GET request
        $command = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $fileKey,  // Path to the image file
        ]);

        return $this->s3Client->createPresignedRequest(
            $command,
            '+' . $this->expirationSeconds . ' seconds'
        )->getUri();
    }
}
