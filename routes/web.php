<?php

use App\Http\Controllers\home;
use App\Http\Controllers\paymentController;
use Illuminate\Support\Facades\Route;


Route::get('/', [home::class, 'index'])->name('home');
Route::get('/payment/pay', [paymentController::class, 'pay'])->name('payment.pay');
Route::post('/midtrans-callback', [paymentController::class, 'callback']);
Route::get('/participant-qr/{encrypted}', function ($encrypted) {
    $id = Crypt::decryptString($encrypted);
    $participant = Participant::findOrFail($id);

    return view('participant.qr-show', compact('participant'));
})->name('participant.qr.show');