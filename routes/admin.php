<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\Admin\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register',  [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('post.register');
});

Route::middleware('auth', 'role:' . RoleEnum::Admin->value)->group(function () {
    Route::get('/dashboard',   [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('polls', PollController::class)->except(['show']);
});
