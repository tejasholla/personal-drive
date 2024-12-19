<?php

namespace App\Http\Controllers\S3Controller;

use App\Factories\S3AuthenticationServiceFactory;
use App\Factories\S3ServiceFactory;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Repositories\BucketRepository;
use App\Services\S3AuthenticationService;
use App\Services\S3FileService;
use App\Services\S3Service;
use Aws\CloudWatch\CloudWatchClient;
use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Ec2\Ec2Client;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class FileManagerController extends Controller
{
    protected S3FileService $s3FileService;


    public function __construct(S3FileService $s3FileService)
    {
        $this->s3FileService = $s3FileService;
    }

    public function index(Request $request, $bucket, $path = ''): Response
    {
        $bucketFiles = $this->s3FileService->getFilesOfPathLocal($bucket, $path);
        return Inertia::render('Aws/FileManager', [
            'files' => $bucketFiles,
            'bucketName' => $bucket,
            'path' => $path,
        ]);
    }
}
