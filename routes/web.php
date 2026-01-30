<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PinController;

// ============ USER ROUTES ============

// Home and browsing
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [HomeController::class, 'category'])->name('category');
Route::get('/media/{medium}', [HomeController::class, 'show'])->name('media.show');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Downloads
Route::get('/download/{medium}/verify', [DownloadController::class, 'showVerification'])->name('download.verify');
Route::post('/download/{medium}/verify', [DownloadController::class, 'verify'])->name('download.verify.post');
Route::get('/api/pin-status', [DownloadController::class, 'status'])->name('api.pin.status');

// The "Bulletproof" Download Route (Outside session interference)
Route::get('/stream/{medium}', [DownloadController::class, 'streamFile'])
    ->name('download.stream')
    ->middleware('signed');

// ============ ADMIN ROUTES ============

// Auth routes (public)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected admin routes
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Change password
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Categories
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Media
    Route::resource('media', MediaController::class)->names([
        'index' => 'media.index',
        'create' => 'media.create',
        'store' => 'media.store',
        'edit' => 'media.edit',
        'update' => 'media.update',
        'destroy' => 'media.destroy',
    ]);

    // PINs
    Route::get('/pins/create-bulk', [PinController::class, 'createBulk'])->name('pins.create-bulk');
    Route::post('/pins/bulk', [PinController::class, 'storeBulk'])->name('pins.store-bulk');
    Route::get('/pins-print-bulk', [PinController::class, 'printBulk'])->name('pins.print-bulk');
    Route::get('/pins-export', [PinController::class, 'export'])->name('pins.export');
    
    Route::resource('pins', PinController::class)->names([
        'index' => 'pins.index',
        'create' => 'pins.create',
        'store' => 'pins.store',
        'show' => 'pins.show',
        'edit' => 'pins.edit',
        'update' => 'pins.update',
        'destroy' => 'pins.destroy',
    ]);
    
    Route::get('/pins/{pin}/print', [PinController::class, 'print'])->name('pins.print');
    Route::post('/pins/{pin}/revoke', [PinController::class, 'revoke'])->name('pins.revoke');
});
