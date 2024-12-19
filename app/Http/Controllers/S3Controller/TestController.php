<?php

namespace App\Http\Controllers\S3Controller;

use App\Services\S3BucketService;
use App\Services\S3UploadService;
use Aws\S3\S3Client;

class TestController
{
    protected S3UploadService $s3UploadService;
    protected S3BucketService $s3BucketService;

    protected S3Client $s3Client;

    public function __construct(S3UploadService $s3UploadService, S3BucketService $s3BucketService, S3Client $s3Client)
    {
        $this->s3UploadService = $s3UploadService;
        $this->s3BucketService = $s3BucketService;
        $this->s3Client = $s3Client;
    }

    public function index()
    {
        $prefix = '';
        $args = [
            'Bucket' => 'pench',
            'Prefix' => $prefix,
        ];
        $s3Objects = $this->s3Client->listObjectsV2($args);
        dd($prefix, $s3Objects['Contents']);
    }
}
