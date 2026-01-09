<?php

use App\Http\Controllers\Admin\BulkActionController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileSettingsController;
use App\Http\Controllers\User\RecoveryController;
use App\Http\Controllers\User\UserDashController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check_user_status'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [UserDashController::class, 'logout'])->name('logout');

    Route::post('bulk-actions/{resource}', [BulkActionController::class, 'handle'])->name('bulk-actions');
    Route::get('recovery/{resource}', [RecoveryController::class, 'index'])->name('recovery.index');

    Route::resource('profile', ProfileSettingsController::class);
    Route::resource('bookings', OrderController::class);
    Route::get('bookings/{id}/cancel', [OrderController::class, 'cancel'])->name('bookings.cancel');
    Route::get('bookings/pay/{id}', [OrderController::class, 'pay'])->name('bookings.pay');
    Route::post('bookings/pay/process/{id}', [OrderController::class, 'paymentProcess'])->name('bookings.paymentProcess');
    Route::get('profile/change/password', [ProfileSettingsController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('profile/change/password/update', [ProfileSettingsController::class, 'updatePassword'])->name('profile.updatePassword');
});
