<?php

namespace App\Http\Controllers\S3Controller;

use App\Services\S3BucketService;
use App\Services\S3UploadService;
use Aws\S3\S3Client;
use Inertia\Inertia;

class TestController
{

    public function index()
    {
        return Inertia::render('Aws/FileManager' );
    }
}
