<?php

use App\Http\Controllers\Frontend\PostShowController;
use App\Http\Controllers\Frontend\WelcomeController;
use App\Http\Controllers\PostController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);
Route::get('/posts/{post:slug}', [PostShowController::class]);

Route::apiResource('/dashboard/posts', PostController::class)
    ->middleware(['auth:sanctum'])
    ->except(['create', 'edit']);

Route::middleware(['auth:sanctum'])->group(function () {
  Route::post('/dashboard/posts/{slug}/image', [PostController::class, 'updateImage']);
  Route::delete('/dashboard/posts/{slug}/image', [PostController::class, 'deleteImage']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', function () {
    return User::all();
});

