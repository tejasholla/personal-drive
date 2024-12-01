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

    Route::get('/files',[FileController::class,'index'])->name('files');
    Route::post('/search-files',[S3Controller\SearchFilesController::class,'index'])->name('search-files');
    Route::post('/refresh-bucket-stats',[S3Controller\BucketController::class,'index'])->name('refresh-bucket-stats');

    Route::get('/s3dashboard',[S3Controller\DashboardController::class,'index'])->name('s3dashboard');
//    Route::get('/bucket/{bucket}',[S3Controller::class,'bucket'])->name('bucket');
    Route::get('/bucket/{bucket}/{path?}', [S3Controller\FileManagerController::class, 'index'])
        ->where('path', '.*')
        ->name('bucket');
});

require __DIR__.'/auth.php';
