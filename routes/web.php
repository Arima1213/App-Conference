<?php

use App\Http\Controllers\home;
use App\Http\Controllers\paymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [home::class, 'index'])->name('home');

Route::get('/payment/pay', [paymentController::class, 'pay'])->name('payment.pay');