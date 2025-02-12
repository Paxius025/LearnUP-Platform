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

// Like Controller
use App\Http\Controllers\LikeController;

// Favorite Post Controller
use App\Http\Controllers\FavoritePostController;

// Profile Controller
use App\Http\Controllers\User\ProfileController;

// Posts Management Controller
use App\Http\Controllers\Admin\PostManagementController;

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
Route::get('/search', [PostController::class, 'search'])->name('user.posts.search');

Route::middleware(['auth'])->prefix('posts')->name('user.posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/store', [PostController::class, 'store'])->name('store');
    Route::get('/edit/{post}', [PostController::class, 'edit'])->name('edit');
    Route::put('/update/{post}', [PostController::class, 'update'])->name('update'); // ✅ เปลี่ยนเป็น PUT
    Route::delete('/delete/{post}', [PostController::class, 'destroy'])->name('delete');
    Route::get('/{post}', [PostController::class, 'show'])->name('show'); // แสดงโพสต์เดี่ยว
    Route::get('/detail/{post}', [PostController::class, 'detail'])->name('detail'); // รายละเอียดโพสต์
});


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardManagementController::class, 'index'])->name('dashboard');
    Route::get('/approvals', [PostApprovalController::class, 'index'])->name('approval');
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/stat', [LogController::class, 'stat'])->name('stat');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.delete');
    
    Route::get('/posts/{post}', [PostApprovalController::class, 'detail'])->name('posts.detail');
    Route::post('/posts/{post}/approve', [PostApprovalController::class, 'approve'])->name('posts.approve');
    Route::post('/posts/{post}/reject', [PostApprovalController::class, 'reject'])->name('posts.reject');
    Route::get('/posts/{post}/detail', [PostApprovalController::class, 'detail'])->name('admin.posts.detail');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// notification
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/count', [NotificationController::class, 'getNotificationCount']);
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread/user', [NotificationController::class, 'getUnreadNotificationsForUser'])->name('notifications.getUnreadNotificationsForUser');
    Route::get('/notifications/unread/admin', [NotificationController::class, 'getUnreadNotificationsForAdmin'])->name('notifications.getUnreadNotificationsForAdmin');
    Route::patch('/notifications/{id}/read/user', [NotificationController::class, 'markNotificationAsReadForUser'])->name('notifications.markNotificationAsReadForUser');
    Route::patch('/notifications/{id}/read/admin', [NotificationController::class, 'markNotificationAsReadForAdmin'])->name('notifications.markNotificationAsReadForAdmin');
    Route::patch('/notifications/mark-all-read/user', [NotificationController::class, 'markAllNotificationsAsReadForUser'])->name('notifications.markAllNotificationsAsReadForUser');
    Route::patch('/notifications/mark-all-read/admin', [NotificationController::class, 'markAllNotificationsAsReadForAdmin'])->name('notifications.markAllNotificationsAsReadForAdmin');
});


// Comment
Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store'); // เพิ่มคอมเมนต์
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy'); // ลบคอมเมนต์
    Route::patch('/comments/{comment}/update', [CommentController::class, 'update'])->middleware('auth');
});

// Like
Route::middleware('auth')->post('/like/{postId}', [LikeController::class, 'toggleLike']);
Route::get('/most-liked-posts', [LikeController::class, 'mostLikedPosts'])->name('most.liked.posts');

// Favorite Post
Route::middleware(['auth'])->group(function () {
    Route::get('/favorites', [FavoritePostController::class, 'index'])->name('favorites.index');
    Route::post('/favorite/{postId}', [FavoritePostController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/bookmarks', [FavoritePostController::class, 'bookmarkedPosts'])->name('user.bookmarks');
}); 

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
});

// Posts Management
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/manage/posts', [\App\Http\Controllers\Admin\PostManagementController::class, 'index'])->name('manage.posts');
    Route::get('/manage/posts/{id}', [\App\Http\Controllers\Admin\PostManagementController::class, 'show'])->name('manage.posts.detail');
});