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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;


class FileManagerController extends Controller
{
    protected ?S3Client $s3Client;
    protected S3FileService $s3FileService;


    public function __construct()
    {
        try {
            $this->s3Client = S3AuthenticationServiceFactory::make();
            $this->s3FileService = new S3FileService($this->s3Client);
        } catch (\Exception $e) {
            Log::warning("Could not log into s3");
            $this->s3Client = null; // or handle accordingly
        }
    }

    public function index (Request $request, $bucket, $path = '')
    {
        $user = Auth::user(); // Assuming you have the Auth facade imported
        $validator = Validator::make($request->all(), [
            'path' => 'string',
            'bucket' => 'string',
        ]);
        if ($this->s3Client === null){
            return $this->renderDashboard(['error' => 'could not log into s3']);
        }

        $bucketFiles = $this->s3FileService->getFilesOfPathLocal($bucket, $path);
        return Inertia::render('Aws/FileManager', [
            'files' => $bucketFiles,
            'bucketName' => $bucket,
        ]);

    }

}
