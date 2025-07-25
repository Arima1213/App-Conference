<?php

use App\Http\Controllers\home;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [home::class, 'index'])->name('home');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/participant')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Payment Routes
Route::get('/payment/pay', [paymentController::class, 'pay'])->name('payment.pay');
Route::post('/midtrans-callback', [paymentController::class, 'callback']);

// Attendance Routes
Route::get('/participant-qr/{encrypted}', [AttendanceController::class, 'showParticipantQr'])->name('participant.qr.show');
Route::post('/participant-qr/validate/{id}', [AttendanceController::class, 'validateAttendance'])->name('participant.qr.validate');
Route::get('/participant-qr/seminar-kit/{encrypted}', [AttendanceController::class, 'showSeminarKitQr'])->name('participant.qr.seminar-kit');
Route::post('/participant-qr/validate/{id}', [AttendanceController::class, 'validateSeminarKit'])->name('participant.qr.seminar-kit.validate');
