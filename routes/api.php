<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentVoteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login', function () {
    return response()->json('Please login');
})->name('login');


Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'get']);
    Route::get('/user/{id}', [UserController::class, 'getUserById']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'get']);
    Route::get('/posts/user/{userid}', [PostController::class, 'getPostsByUser']);
    Route::post('/posts', [PostController::class, 'create']);
    Route::delete('/posts/{id}', [PostController::class, 'delete']);

    Route::get('/posts/{postId}/comments', [CommentController::class, 'getAll']);
    Route::get('/comments/{id}', [CommentController::class, 'get']);
    Route::post('/posts/{postId}/comments', [CommentController::class, 'create']);
    Route::delete('/comments/{id}', [CommentController::class, 'delete']);


    Route::get('/posts/{postId}/likes', [VoteController::class, 'getLikes']);
    Route::get('/posts/{postId}/dislikes', [VoteController::class, 'getDislikes']);
    Route::post('/posts/{postId}/like', [VoteController::class, 'like']);
    Route::post('/posts/{postId}/dislike', [VoteController::class, 'dislike']);
    Route::get('/posts/{postId}/is-liked', [VoteController::class, 'isLiked']);
    Route::get('/posts/{postId}/is-disliked', [VoteController::class, 'isDisliked']);

    Route::get('/comments/{commentId}/likes', [CommentVoteController::class, 'getLikes']);
    Route::get('/comments/{commentId}/dislikes', [CommentVoteController::class, 'getDislikes']);
    Route::post('/comments/{commentId}/like', [CommentVoteController::class, 'like']);
    Route::post('/comments/{commentId}/dislike', [CommentVoteController::class, 'dislike']);
    Route::get('/comments/{commentId}/is-liked', [CommentVoteController::class, 'isLiked']);
    Route::get('/comments/{commentId}/is-disliked', [CommentVoteController::class, 'isDisliked']);

});
