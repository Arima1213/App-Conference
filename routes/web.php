<?php

use App\Http\Controllers\home;
use App\Http\Controllers\registerConferenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [home::class, 'index'])->name('home');
//route daftar ke konferensi
Route::get('/register', [registerConferenceController::class, 'register'])->name('register');
