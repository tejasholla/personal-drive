<?php
namespace App\Services;

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

    public function getFilesOfPath(string $bucketName, string $path = ''): array
    {
        $args = [
            'Bucket' => $bucketName,
            'Delimiter' => '/'
        ];
        if ($path && $path != '/'){
            $args['Prefix'] = $path;
        }

        $s3Objects = $this->s3Client->listObjectsV2($args);
        $fileItems = [];
        if (isset($s3Objects['Contents'])) {
            $fileItems = array_filter($s3Objects['Contents'], function ($item) use ($s3Objects) {
                return isset($item['Key']) && $item['Key'] !== $s3Objects['Prefix'];
            });
        }

        return array_merge($fileItems, $s3Objects['CommonPrefixes'] ?? []);
    }
    public function getFilesOfPathLocal(string $bucketName, string $path = ''): array
    {
        $bucket = Bucket::where('s3Name', $bucketName)->first();
        if (!$bucket){
            return [];
        }
        $query = File::where('user_id', auth()->id())
            ->where('bucket_id', $bucket->id);

        if (!$path || $path === '/'){
            $path = '';
        }
        $query->where('path', $path);

//dd(auth()->id(), $bucket->id, $path, $query->toSql());
        $fileItems = $query->get();

//        dd($fileItems);

        return $fileItems->toArray();
        
        $args = [
            'Bucket' => $bucketName,
            'Delimiter' => '/'
        ];
        if ($path && $path != '/'){
            $args['Prefix'] = $path;
        }
        

        $s3Objects = $this->s3Client->listObjectsV2($args);
        $fileItems = [];
        if (isset($s3Objects['Contents'])) {
            $fileItems = array_filter($s3Objects['Contents'], function ($item) use ($s3Objects) {
                return isset($item['Key']) && $item['Key'] !== $s3Objects['Prefix'];
            });
        }

        return array_merge($fileItems, $s3Objects['CommonPrefixes'] ?? []);
    }

    public function searchFiles(string $bucketName, string $searchQuery): array
    {
        $args = [
            'Bucket' => $bucketName,
            'Delimiter' => '/',
        ];

        $matchedFiles = [];
        do {
            $s3Objects = $this->s3Client->listObjectsV2($args);
            if (isset($s3Objects['Contents'])) {
                foreach ($s3Objects['Contents'] as $object) {
                    if (stripos($object['Key'], $searchQuery) !== false) {
                        $matchedFiles[] = $object['Key'];
                    }
                }
            }
            $args['ContinuationToken'] = $s3Objects['NextContinuationToken'] ?? null;
        } while (isset($s3Objects['NextContinuationToken']));

        return $matchedFiles;
    }
}
