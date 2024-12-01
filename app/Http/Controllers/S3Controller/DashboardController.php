<?php

namespace App\Http\Controllers\S3Controller;

use App\Factories\S3AuthenticationServiceFactory;
use App\Factories\S3ServiceFactory;
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
    protected ?S3Client $s3Auth;
    protected S3BucketService $s3BucketService;
    private BucketRepository $bucketRepository;

    public function __construct(BucketRepository $bucketRepository)
    {
        $this->bucketRepository = $bucketRepository;
        try {
            $this->s3Auth = S3AuthenticationServiceFactory::make();
//            dd($this->s3auth);
            $this->s3BucketService = new S3BucketService($this->s3Auth, new BucketRepository());

        } catch (\Exception $e) {
            Log::warning("Could not log into s3");
            $this->s3Auth = null; // or handle accordingly
        }
    }

    public function index(Request $request): Response
    {
        if ($this->s3Auth === null) {
            return $this->renderDashboard(['error' => 'could not log into s3']);
        }
        $bucketStats = [];
        $allBuckets = $this->s3BucketService->getAllBuckets();
        foreach ($allBuckets as $aBucket) {
//            $bucketStats[$aBucket['s3Name']] = $this->s3BucketService->getBucketStats($aBucket);
            $bucketStats[$aBucket['s3Name']] = ['totalSize' => round(($this->bucketRepository->getBucketSize($aBucket['id']) / 1024) / 1024) . ' MB', 'totalNumFiles' => $this->bucketRepository->getBucketFilesCount($aBucket['id'])];
        }
        return $this->renderDashboard(['bucketStats' => $bucketStats]);
    }

    private function renderDashboard(array $props): Response
    {
        return Inertia::render('Aws/Dashboard', $props);
    }

}
