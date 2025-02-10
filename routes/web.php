<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

// User Controllers
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PostController;

// Admin Controllers
use App\Http\Controllers\Admin\PostApprovalController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardManagementController;

// Comment Controller
use App\Http\Controllers\CommentController;

// Notification Controller
use App\Http\Controllers\NotificationController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Register User
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);  

// Login User
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

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
    Route::get('/detail/{post}', [PostController::class, 'detail'])->name('detail'); // Route สำหรับแสดงรายละเอียดของโพสต์
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardManagementController::class, 'index'])->name('dashboard');
    Route::get('/approvals', [PostApprovalController::class, 'index'])->name('approval');
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/stat', [LogController::class, 'stat'])->name('stat');
    
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.delete');
    
    Route::get('/posts/{post}', [PostApprovalController::class, 'detail'])->name('posts.detail');
    Route::post('/posts/{post}/approve', [PostApprovalController::class, 'approve'])->name('posts.approve');
    Route::post('/posts/{post}/reject', [PostApprovalController::class, 'reject'])->name('posts.reject');
    Route::get('/posts/{post}/detail', [PostApprovalController::class, 'detail'])->name('admin.posts.detail');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// notification
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/delete-read', [NotificationController::class, 'deleteReadNotifications']);
});

// Comment
Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store'); // เพิ่มคอมเมนต์
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy'); // ลบคอมเมนต์
});