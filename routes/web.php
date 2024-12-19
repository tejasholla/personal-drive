<?php

use App\Http\Controllers\S3Controller;
use App\Http\Controllers\FileManager\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/search-files', [S3Controller\SearchFilesController::class, 'index'])->name('search-files');
    Route::post('/refresh-bucket-stats', [S3Controller\BucketController::class, 'index'])->name('refresh-bucket-stats');

    Route::get('/s3dashboard', [S3Controller\DashboardController::class, 'index'])->name('s3dashboard');
    Route::get('/bucket/{bucket}/{path?}', [S3Controller\FileManagerController::class, 'index'])
        ->where('path', '.*')
        ->name('bucket');

    Route::post('/s3/upload', [S3Controller\UploadController::class, 'store'])->name('s3.upload');
    Route::post('/s3/create-folder', [S3Controller\UploadController::class, 'createFolder'])->name('s3.upload');
    Route::post('/s3/delete-files', [S3Controller\DeleteController::class, 'deleteFiles'])->name('s3.delete');

    // test
    Route::get('test', [S3Controller\TestController::class, 'index']);
});

Route::get('/s3autherror', function () {
    return Inertia::render('Aws/S3ErrorPage');
})->name('s3autherror');

require __DIR__ . '/auth.php';
