<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\CommentWebController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/artikel/{slug}', [HomeController::class, 'show'])->name('article');

// Authentication Routes
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthWebController::class, 'register']);
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

// Comment Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::post('/artikel/{slug}/comment', [CommentWebController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{id}', [CommentWebController::class, 'destroy'])->name('comment.destroy');
});
