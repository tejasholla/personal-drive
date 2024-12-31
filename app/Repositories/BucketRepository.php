<?php

namespace App\Repositories;

use App\Models\Bucket;
use App\Models\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BucketRepository
{
    public function insertBuckets(array $buckets): void
    {
        Bucket::insertOrIgnore($buckets);
    }

    public function getAllBuckets(): array
    {
        return Bucket::all()->toArray();
    }

    public function getBucketSize(int $bucketId)
    {
        return File::where('bucket_id', $bucketId)
            ->sum('size');
    }

    public function getBucketFilesCount(int $bucketId)
    {
        return File::where('bucket_id', $bucketId)
            ->count();
    }
}
