<?php

namespace App\Services;

use App\Models\File;
use App\Repositories\BucketRepository;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Auth;

class S3BucketService
{
    protected S3Client $s3Client;
    protected BucketRepository $bucketRepository;
    private $userId;

    public function __construct(S3Client $s3Client, BucketRepository $bucketRepository)
    {
        $this->s3Client = $s3Client;
        $this->bucketRepository = $bucketRepository;
        $this->userId = Auth::user()->id;
    }

    public function getAllBuckets(): array
    {
        $result = $this->s3Client->listBuckets();
        $buckets = $this->transformBuckets($result['Buckets']);
        $this->bucketRepository->insertBuckets($buckets);

        return $this->bucketRepository->getAllBuckets() ?? [];
    }

    public function generateBucketStats(array $bucket): array
    {
        $args = [
            'Bucket' => $bucket['s3Name'],
            'Prefix' => '',

        ];

        $totalSize = 0;
        $totalNumFiles = 0;
        $filesArr = [];

        do {
            // Fetch the list of objects for the current iteration
            $s3Objects = $this->s3Client->listObjectsV2($args);
            if (isset($s3Objects['Contents'])) {
                foreach ($s3Objects['Contents'] as $s3Object) {
                    $totalSize += $s3Object['Size'];
                    $totalNumFiles++;
                    $this->transformFiles($s3Object, $bucket, $filesArr);
                }
                File::insertOrIgnore($filesArr);
            }

            $args['ContinuationToken'] = $s3Objects['NextContinuationToken'] ?? null;
        } while (isset($s3Objects['NextContinuationToken']));

        return ['totalSize' => round(($totalSize / 1024) / 1024).' MB', 'totalNumFiles' => $totalNumFiles];
    }

    private function transformBuckets(array $s3Buckets): array
    {
        $buckets = [];
        foreach ($s3Buckets as $bucket) {
            $buckets[] = [
                's3Name' => $bucket['Name'],
                's3CreationDate' => $bucket['CreationDate'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return $buckets;
    }

    private function transformFiles(array $s3Object, array $bucket, array &$filesArr): void
    {
        preg_match_all('#([^/]+)(/|$)#', $s3Object['Key'], $matches);

        $currentPath = '';
        foreach ($matches[1] as $index => $fileName) {
            $isDir = isset($matches[2][$index]) && $matches[2][$index] === '/';

            $filesArr[] = [
                'fileName' => $fileName,
                'is_dir' => $isDir,
                'path' => rtrim($currentPath, '/'),
                's3key' => $s3Object['Key'] ?? '',
                's3LastModified' => $s3Object['LastModified'],
                'bucket_id' => $bucket['id'],
                'user_id' => $this->userId,
                'size' => !$isDir ? $s3Object['Size'] : '',
            ];

            $currentPath .= $fileName . '/';
        }

    }


}
