<?php

use App\Http\Controllers\home;
use Illuminate\Support\Facades\Route;

Route::get('/', [home::class, 'index'])->name('home');
