<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogMediaController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\MoldController;
use App\Http\Controllers\MoldDownloadController;
use App\Http\Controllers\MoldMediaController;
use App\Http\Controllers\MoldTagController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\BlogLikeController;
use App\Http\Controllers\MoldLikeController;



// Public Routes
Route::post('login', [UserController::class, 'login']);

Route::get('/molds/{id}/download', [MoldController::class, 'download']);
Route::post('signup', [UserController::class, 'store']);


// Protecting Routes with Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'user']);

    Route::apiResource('ads', AdController::class);
    Route::apiResource('blog-categories', BlogCategoryController::class);
    Route::apiResource('blog-comments', BlogCommentController::class);
    Route::apiResource('blog-media', BlogMediaController::class);
    Route::apiResource('blog-posts', BlogPostController::class);
    // Like a post
    Route::post('/posts/{postId}/like', [BlogLikeController::class, 'likePost']);

    // Unlike a post
    Route::post('/posts/{postId}/unlike', [BlogLikeController::class, 'unlikePost']);

    // Check if the user liked a post
    Route::get('/posts/{postId}/liked', [BlogLikeController::class, 'hasLiked']);
    Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags', [TagController::class, 'store']);
Route::get('/tags/{id}', [TagController::class, 'show']);
Route::put('/tags/{id}', [TagController::class, 'update']);
Route::delete('/tags/{id}', [TagController::class, 'destroy']);
Route::get('/tags/{tagId}/posts', [TagController::class, 'getPostsByTag']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('countries', CountryController::class);
    Route::apiResource('events', EventController::class);
    Route::apiResource('migrations', MigrationController::class);
    Route::apiResource('molds', MoldController::class);
     Route::post('molds/{moldId}/like', [MoldLikeController::class, 'likeMold']);
    Route::delete('molds/{moldId}/unlike', [MoldLikeController::class, 'unlikeMold']);
    Route::get('molds/{moldId}/has-liked', [MoldLikeController::class, 'hasLiked']);
    Route::get('/molds/{id}/increment-download', [MoldController::class, 'incrementDownload']);
    Route::apiResource('mold-downloads', MoldDownloadController::class);
    Route::apiResource('mold-media', MoldMediaController::class);
    Route::apiResource('mold-tags', MoldTagController::class);
    Route::apiResource('subscriptions', SubscriptionController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('user-profiles', UserProfileController::class);
});
