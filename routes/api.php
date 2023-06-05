<?php

use App\Http\Controllers\Api\PostController;
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

Route::get('posts',[PostController::class, 'index']);

Route::get('post/{post}',[PostController::class, 'get']);

Route::post('post/create',[PostController::class, 'store']);

Route::put('post/edit/{post}',[PostController::class, 'update']);

Route::delete('post/delete/{post}',[PostController::class, 'delete']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
