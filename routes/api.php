<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// 게시글 API
Route::apiResource('posts', PostController::class);

// 댓글 API
Route::scopeBindings()->group(function () {
    Route::apiResource('posts.comments', CommentController::class);
});
