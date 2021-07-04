<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('anonymous')->get('/', [PostController::class, 'test']);

Route::group(['prefix' => 'posts', 'middleware' => 'anonymous'], function () {
    Route::get('', [PostController::class, 'all']);
    Route::post('', [PostController::class, 'store']);
    Route::get('{postId}', [PostController::class, 'show']);
    Route::patch('{postId}', [PostController::class, 'update']);
    Route::delete('{postId}', [PostController::class, 'destroy']);
    Route::post('{postId}/vote', [PostController::class, 'vote']);

//    Route::group(['prefix' => '{postId}/comments'], function () {
//        Route::get('', [PostController::class, 'all']);
//        Route::post('', [PostController::class, 'store']);
//        Route::get('{postId}', [PostController::class, 'show']);
//        Route::patch('{postId}', [PostController::class, 'update']);
//        Route::delete('{postId}', [PostController::class, 'destroy']);
//        Route::post('{postId}/vote', [PostController::class, 'vote']);
//    });
});
