<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
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
Route::get('posts', [PostController::class, 'index']);
Route::get('post/{post}', [PostController::class, 'show']);

// USER
Route::post('register', [AuthController::class, 'create']);
Route::post('login', [AuthController::class, 'store']);
Route::get('users', [AuthController::class, 'index']);
Route::get('user/{user}', [AuthController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {

    // GET CURRENT CONNECTED USER
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // POSTS - CUD
    Route::post('post/create', [PostController::class, 'store']);
    Route::put('post/edit/{post}', [PostController::class, 'update']);
    Route::delete('post/delete/{post}', [PostController::class, 'destroy']);

    // USER - UDDis
    Route::put('user/edit/{user}', [AuthController::class, 'update']);
    Route::delete('user/delete/{user}', [AuthController::class, 'destroy']);
    Route::put('user/enable/{user}/{enable}', [AuthController::class, 'enable']);
});
