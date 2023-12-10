<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
Route::post('register', [AuthController::class, 'create']); // SIGNIN
Route::post('login', [AuthController::class, 'store']); // LOGIN
Route::delete('logout', [AuthController::class, 'destroy']); // LOGOUT
Route::delete('delete', [AuthController::class, 'remove']); // DELETE ACCOUNT
Route::get('users', [AuthController::class, 'index']); // GET ALL USERS
Route::get('user/{user}', [AuthController::class, 'show']); // GET ONE USER
Route::get('/user', [AuthController::class, 'current']); // GET CURRENT CONNECTED USER


Route::middleware('auth:sanctum')->group(function () {


    // POSTS - CUD
    Route::post('post/create', [PostController::class, 'store']);
    Route::put('post/edit/{post}', [PostController::class, 'update']);
    Route::delete('post/delete/{post}', [PostController::class, 'destroy']);

    // USER - UDDis
    Route::put('user/edit/{user}', [AuthController::class, 'update']);
    Route::delete('user/delete/{user}', [AuthController::class, 'destroy']);
    Route::put('user/enable/{user}/{enable}', [AuthController::class, 'enable']);
});
