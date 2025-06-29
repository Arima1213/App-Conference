<?php

use App\Http\Controllers\home;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [home::class, 'index'])->name('home');
Route::get('/payment/pay', [paymentController::class, 'pay'])->name('payment.pay');
Route::post('/midtrans-callback', [paymentController::class, 'callback']);
Route::get('/participant-qr/{encrypted}', [AttendanceController::class, 'showParticipantQr'])->name('participant.qr.show');
Route::post('/participant-qr/validate/{id}', [AttendanceController::class, 'validateAttendance'])->name('participant.qr.validate');