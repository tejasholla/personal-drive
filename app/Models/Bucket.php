<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    public static function getBucketFromName(string $bucketName): array
    {
        return static::where('s3Name', $bucketName)->first()->toArray();
    }
}
