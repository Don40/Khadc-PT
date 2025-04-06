<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\OtpController;

Route::get('/', [RegistrationController::class, 'showForm'])->name('register.form');
Route::post('/', [RegistrationController::class, 'submitForm'])->name('register.submit');

Route::get('/registrations', [RegistrationController::class, 'showAll'])->name('registrations.all');
Route::delete('/registrations/{id}', [RegistrationController::class, 'delete'])->name('registrations.delete');
Route::get('/registrations/view-only', [RegistrationController::class, 'viewOnly'])->name('registrations.viewonly');

// OTP Routes
Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('otp.send'); // ✅ POST only
Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/register-success', function () {
    return view('register-success');
})->name('register.success');
