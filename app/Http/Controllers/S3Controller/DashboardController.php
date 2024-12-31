<?php

namespace App\Http\Controllers\S3Controller;

use App\Factories\S3AuthenticationServiceFactory;
use App\Factories\S3ServiceFactory;
use App\Helpers\FileSizeFormatter;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Repositories\BucketRepository;
use App\Services\S3AuthenticationService;
use App\Services\S3BucketService;
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

class DashboardController extends Controller
{
    protected S3BucketService $s3BucketService;
    private BucketRepository $bucketRepository;

    public function __construct(BucketRepository $bucketRepository, S3BucketService $s3BucketService)
    {
        $this->bucketRepository = $bucketRepository;
        $this->s3BucketService = $s3BucketService;
    }

    public function index(Request $request): Response
    {
        $bucketStats = [];
        $allBuckets = $this->s3BucketService->getAllBuckets();
        foreach ($allBuckets as $aBucket) {
            $bucketStats[$aBucket['s3Name']] = [
                'totalSize' => FileSizeFormatter::format($this->bucketRepository->getBucketSize($aBucket['id'])),
                'totalNumFiles' => $this->bucketRepository->getBucketFilesCount($aBucket['id'])
            ];
        }
        return $this->renderDashboard(['bucketStats' => $bucketStats]);
    }

    private function renderDashboard(array $props): Response
    {
        return Inertia::render('Aws/Dashboard', $props);
    }
}
