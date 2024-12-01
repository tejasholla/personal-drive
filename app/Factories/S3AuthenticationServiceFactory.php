<?php

namespace App\Factories;

use App\Repositories\BucketRepository;
use App\Services\S3AuthenticationService;
use Aws\S3\S3Client;

class S3AuthenticationServiceFactory
{
    /**
     * @throws \Exception
     */
    public static function make(): S3Client
    {
        $s3Auth = new S3AuthenticationService();
        return $s3Auth->signIn();
    }
}
