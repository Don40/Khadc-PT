<?php

use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;


Route::get('/', function () {
    $registrations = [];
    if (auth()->check()) {
        $registrations = auth()->user()->registrations()->latest()->latest()->get();
        }
    return view('home',['registrations' => $registrations]);
});


Route::post('/register',[UserController::class, 'register'])->name('user.register');
Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
Route::post('/login', [UserController::class, 'login'])->name('user.login');


Route::get('/register_PF', [RegistrationController::class, 'showForm'])
->middleware('auth')
->name('register.form');
Route::post('/register_PF', [RegistrationController::class, 'submitForm'])->name('register.submit');

Route::get('/registration-viewown', [RegistrationController::class, 'viewOwn'])
->middleware('auth')
->name('registrations.viewown');
// // Route::get('/registrations/view-only', [RegistrationController::class, 'viewOnly'])
// //  ->middleware('auth')
// ->name('registrations.viewonly');
Route::get('/registrations', [RegistrationController::class, 'showAll'])
 ->middleware('auth')
 ->name('registrations.all');
Route::delete('/registrations/{id}', [RegistrationController::class, 'delete'])
 ->middleware('auth')
->name('registrations.delete');

// OTP Routes
// Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('otp.send'); // ✅ POST only
// Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
// Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/register-success', function () {
    return view('register-success');
})->name('register.success');
