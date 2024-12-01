<?php

namespace App\Services;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3AuthenticationService
{
    /**
     * Sign in to AWS S3 and return the S3Client instance.
     *
     * @throws \Exception
     */
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
            // Perform a simple operation to ensure the credentials are correct
            $b = $s3Client->listBuckets();
        } catch (AwsException $e) {
            // Handle authentication error
            throw new \Exception('AWS S3 authentication failed: '.$e->getMessage());
        }
        return $s3Client;
    }
}
