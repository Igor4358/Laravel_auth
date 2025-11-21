<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

// Главная страница - посты (только для авторизованных)
Route::get('/', [PostController::class, 'index'])->name('home');

// Регистрация
Route::prefix('auth')->group(function (){
    Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

// Авторизация
Route::get('login', [LoginController::class, 'loginView'])->name('login.view');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/email/verify/{id}/{hash}', [VerifyController::class, 'verify'])
    ->name('verification.verify');
// Поиск
Route::get('search', [SearchController::class, 'search'])->name('search');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/search-ajax', [SearchController::class, 'searchAjax'])->name('search.ajax');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
// Защищенные маршруты (только для авторизованных)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Посты
    Route::resource('posts', PostController::class);
    // Комментарии (редактирование и удаление)
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    // Редактирование профиля
    Route::get('/profile/edit', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/profile/remove-avatar', [UserController::class, 'removeAvatar'])->name('user.removeAvatar');
});
Route::get('/about', function () {
    return view('about');
})->name('about');
