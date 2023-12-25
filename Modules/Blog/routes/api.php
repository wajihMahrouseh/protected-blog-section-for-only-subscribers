<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Blog\app\Http\Controllers\BlogController;

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

Route::group([
    // Blogs
    'prefix'        => 'blogs',
    'middleware'    => [
        'auth:sanctum',
        // 'role:admin'
    ]
], function () {
    Route::get('', [BlogController::class, 'index'])->middleware('adminOrSubscriber');
    Route::post('', [BlogController::class, 'store'])->middleware('role:admin');
    Route::get('{blog}', [BlogController::class, 'show'])->middleware('adminOrSubscriber');
    Route::post('{blog}', [BlogController::class, 'update'])->middleware('role:admin');
    Route::delete('{blog}', [BlogController::class, 'destroy'])->middleware('role:admin');
});
