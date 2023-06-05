<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// POST
Route::get('posts',[PostController::class, 'index']);
Route::get('post/{post}',[PostController::class, 'get']);
Route::post('post/create',[PostController::class, 'store']);
Route::put('post/edit/{post}',[PostController::class, 'update']);
Route::delete('post/delete/{post}',[PostController::class, 'delete']);

// USER
Route::post('register',[UserController::class, 'register']);
Route::get('users',[UserController::class, 'index']);
Route::get('user/{user}',[UserController::class, 'get']);
Route::get('login',[UserController::class, 'login']);
Route::put('user/edit/{user}',[UserController::class, 'update']);
Route::delete('user/delete/{user}',[UserController::class, 'delete']);
Route::put('user/disable/{user}',[UserController::class, 'disable']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
