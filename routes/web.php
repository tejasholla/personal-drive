<?php

use App\Http\Controllers\AdminController\AdminConfigController;
use App\Http\Controllers\DriveControllers;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareControllers;
use App\Http\Controllers\ShareControllers\ShareFilesGuestController;
use App\Http\Middleware\HandleAuthOrGuestMiddleware;
use App\Http\Middleware\HandleGuestShareMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(callback: function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/admin-config', [AdminConfigController::class, 'index'])->name('admin-config');
    Route::post('/admin-config/update', [AdminConfigController::class, 'update'])->name('admin-config.update');

    // Drive routes
    Route::get('/drive/{path?}', [DriveControllers\FileManagerController::class, 'index'])
        ->where('path', '.*')
        ->name('drive');
    Route::post('/upload', [DriveControllers\UploadController::class, 'store'])->name('s3.upload');
    Route::post('/create-folder', [DriveControllers\UploadController::class, 'createFolder']);
    Route::post('/delete-files', [DriveControllers\FileDeleteController::class, 'deleteFiles']);
    Route::post('/resync', [DriveControllers\ReSyncController::class, 'index']);
    Route::post('/gen-thumbs', [DriveControllers\ThumbnailController::class, 'update']);
    Route::post('/search-files', [DriveControllers\SearchFilesController::class, 'index']);
    Route::get('/search-files', fn() => redirect('/drive'));

    //Share Routes
    Route::post('/pause-share', [ShareControllers\ShareFilesModController::class, 'pause']);
    Route::post('/delete-share', [ShareControllers\ShareFilesModController::class, 'delete']);
    Route::post('/share-files', [ShareControllers\ShareFilesGenController::class, 'index']);
    Route::get('/all-shares', [ShareControllers\SharedListController::class, 'index'])->name('all-shares');


    // Test
    Route::get('test', [DriveControllers\TestController::class, 'index']);
});

// share guest home
Route::get('/shared/{slug}/{path?}', [ShareControllers\ShareFilesGuestController::class, 'index'])->where(
    'path',
    '.*'
)->middleware([HandleGuestShareMiddleware::class]);

// admin or shared
Route::get('/fetch-file/{id}/{slug?}', [DriveControllers\FetchFileController::class, 'index'])
    ->middleware([HandleAuthOrGuestMiddleware::class]);
Route::get('/fetch-thumb/{id}/{slug?}', [DriveControllers\FetchFileController::class, 'getThumb'])
    ->middleware([HandleAuthOrGuestMiddleware::class]);
Route::post('/download-files', [DriveControllers\DownloadController::class, 'index'])
    ->middleware([HandleAuthOrGuestMiddleware::class]);

//public for shared
Route::post('/shared-check-password', [ShareFilesGuestController::class, 'checkPassword']);
Route::get('/shared-password/{slug}', [ShareFilesGuestController::class, 'passwordPage'])
    ->name('shared.password.check');


Route::get(
    '/rejected',
    function () {
        return Inertia::render('Rejected');
    }
)->name('rejected');


Route::get('/testdownload', [DriveControllers\DownloadController::class, 'test']);


require __DIR__ . '/auth.php';
