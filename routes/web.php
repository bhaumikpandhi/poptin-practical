<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PollController;

Route::get('/', function () {
    return redirect()->route('polls.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',   [LoginController::class, 'index'])->name('login');
    Route::post('/login',  [LoginController::class, 'login'])->name('post.login');

    Route::get('/register',  [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('post.register');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');


Route::prefix('polls')->name('polls.')->group(function () {
    Route::get('/', [PollController::class, 'index'])->name('index');
    Route::get('/{poll}', [PollController::class, 'show'])->name('show');

    Route::post('/{poll}/vote', [PollController::class, 'vote'])->name('vote');

    Route::get('/{poll}/results', [PollController::class, 'results'])->name('results');
});
