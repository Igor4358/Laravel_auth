<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
})->name('home');

Route::prefix('auth')->group(function (){
    Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

Route::get('login', [LoginController::class, 'loginView'])->name('login.view');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::any('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('search', [SearchController::class, 'search'])->name('search');
Route::get('search-ajax', [SearchController::class, 'searchAjax'])->name('search.ajax');
