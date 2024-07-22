<?php

use App\Http\Controllers\Backend\Vendor\VendorController;
use App\Http\Controllers\Backend\Vendor\VendorProfileController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [VendorController::class, 'dashboard'])->name('dashboard');

/** Profile Routes */
Route::get('profile', [VendorProfileController::class, 'index'])->name('profile');
Route::put('/profile', [VendorProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile', [VendorProfileController::class, 'updatePassword'])->name('profile.update.password');
