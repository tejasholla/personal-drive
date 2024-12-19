<?php

namespace App\Services;

use App\Exceptions\S3AuthenticationException;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Inertia\Inertia;

class S3AuthenticationService
{
    public function signIn(): S3Client
    {
        $provider = new Credentials(
            env('AWS_ACCESS_ID'),
            env('AWS_SECRET_KEY')
        );

        $s3Client = new S3Client([
            'region' => env('AWS_REGION'),
            'credentials' => $provider
        ]);
        try {
            $b = $s3Client->listBuckets();
        } catch (AwsException $e) {
            throw new S3AuthenticationException('AWS S3 authentication failed: ' . $e->getMessage());

        }
        return $s3Client;
    }
}
