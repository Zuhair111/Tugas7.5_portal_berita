<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// News API Routes
Route::prefix('v1')->group(function () {
    
    // Authentication Routes (Public)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Homepage - Get featured, trending, and latest articles
    Route::get('/news', [NewsController::class, 'index']);
    
    // Categories
    Route::get('/categories', [NewsController::class, 'categories']);
    
    // Articles by category
    Route::get('/category/{slug}', [NewsController::class, 'categoryArticles']);
    
    // Article detail
    Route::get('/article/{slug}', [NewsController::class, 'show']);
    
    // Search articles
    Route::get('/search', [NewsController::class, 'search']);
    
    // Trending articles
    Route::get('/trending', [NewsController::class, 'trending']);
    
    // Popular articles (by views)
    Route::get('/popular', [NewsController::class, 'popular']);
    
    // Comments (Public - Read only)
    Route::get('/article/{slug}/comments', [CommentController::class, 'index']);
    
    // Protected Routes (Requires Authentication)
    Route::middleware('auth:api')->group(function () {
        // Auth
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Comments (Write)
        Route::post('/article/{slug}/comments', [CommentController::class, 'store']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    });
});
