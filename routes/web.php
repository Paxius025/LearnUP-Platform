<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PostController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Register User
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);  

// Login User
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});

Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload.image');

Route::middleware(['auth'])->prefix('posts')->name('user.posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/store', [PostController::class, 'store'])->name('store');
    Route::get('/edit/{post}', [PostController::class, 'edit'])->name('edit');
    Route::post('/update/{post}', [PostController::class, 'update'])->name('update');
    Route::delete('/delete/{post}', [PostController::class, 'destroy'])->name('delete');
    Route::get('/{post}', [PostController::class, 'show'])->name('show'); // Route สำหรับแสดงโพสต์เดี่ยว
});