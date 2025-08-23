<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
    Route::post('/perfrom-auth', [AuthController::class, 'performAuth'])->name('perform-auth');
    Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/notify', [AuthController::class, 'notify'])->name('notify');

Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('/auth/redirect/{social}', [SocialiteController::class, 'index'])->name('auth.socialite');
Route::get('/auth/callback/{social}', [SocialiteController::class, 'callback'])->name('auth.socialite.callback');
