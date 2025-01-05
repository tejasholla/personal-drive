<?php

use App\Http\Controllers\AdminController\AdminConfigController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriveControllers;
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
    Route::get('/admin-config', [AdminConfigController::class, 'index'])->name('admin-config.index');
    Route::post('/admin-config/update', [AdminConfigController::class, 'update'])->name('admin-config.update');

    // Drive routes
    Route::get('/drive/{path?}', [DriveControllers\FileManagerController::class, 'index'])->where('path', '.*');
    Route::post('/upload', [DriveControllers\UploadController::class, 'store'])->name('s3.upload');
    Route::post('/create-folder', [DriveControllers\UploadController::class, 'createFolder']);
    Route::post('/delete-files', [DriveControllers\FileDeleteController::class, 'deleteFiles']);
    Route::post('/download-files', [DriveControllers\DownloadController::class, 'index']);
    Route::post('/resync', [DriveControllers\ReSyncController::class, 'index']);
    Route::get('/fetch-file/{hash}', [DriveControllers\FetchFileController::class, 'index']);
    Route::get('/fetch-thumb/{hash}', [DriveControllers\FetchFileController::class, 'getThumb']);
    Route::post('/gen-thumbs', [DriveControllers\ThumbnailController::class, 'update']);
    Route::post('/search-files', [DriveControllers\SearchFilesController::class, 'index']);
    Route::get('/search-files', fn() => redirect('/drive'));

    // Test
    Route::get('test', [DriveControllers\TestController::class, 'index']);
});

Route::get('/testdownload', [DriveControllers\DownloadController::class, 'test']);

Route::get('/stopwatch', function () {
    return Inertia::render('JS/Stopwatch');
});


require __DIR__ . '/auth.php';
