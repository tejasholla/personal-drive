<?php

namespace App\Services;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;

class S3Service
{
    protected S3Client $s3Client;

    public function __construct()
    {
        $this->s3Client = $this->signInToS3();
    }
    private function signInToS3(): S3Client
    {
        $provider = new Credentials(
            env('AWS_ACCESS_ID'),
            env('AWS_SECRET_KEY')
        );
        return new S3Client([
            'region' => env('AWS_REGION'),
            'credentials' => $provider
        ]);
    }

    public function getAllBuckets(): array
    {
        $result = $this->s3Client->listBuckets();
        return $result['Buckets'] ?? [];
    }


    public function getFilesOfPath(string $bucketName, string $path = ''): array
    {
        $args = [
            'Bucket' => $bucketName,
            'Delimiter' => '/'
        ];
        if ($path && $path != '/'){
            $args['Prefix'] = $path;
        }

//        dd($args);
        $s3Objects = $this->s3Client->listObjectsV2($args);
        return array_merge(($s3Objects['Contents'] ?? []), ($s3Objects['CommonPrefixes'] ?? []) ) ;
    }

    public function getBucketStats(string $bucketName)
    {
        $args = [
            'Bucket' => $bucketName,
        ];

        $totalSize = 0;
        $totalNumFiles = 0;

        do {
            // Fetch the list of objects for the current iteration
            $s3Objects = $this->s3Client->listObjectsV2($args);

            // Add the size of each object to the total size
            if (isset($s3Objects['Contents'])) {
                foreach ($s3Objects['Contents'] as $object) {
                    $totalSize += $object['Size'];
                    $totalNumFiles++;
                }
            }
            // Update the continuation token for the next iteration
            var_dump('NextContinuationToken', $s3Objects['NextContinuationToken']);
            $args['ContinuationToken'] = $s3Objects['NextContinuationToken'] ?? null;
        } while (isset($s3Objects['NextContinuationToken']));

        // Return the total size
        return ['totalSize' => round(($totalSize/1024)/1024) . ' MB ', 'totalNumFiles' => $totalNumFiles];
    }
}
