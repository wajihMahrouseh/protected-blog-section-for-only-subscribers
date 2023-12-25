<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Subscriber\app\Http\Controllers\SubscriberController;

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
    // Subscribers
    'prefix'        => 'subscribers',
    'middleware'    => [
        'auth:sanctum',
        'role:admin'
    ]
], function () {
    Route::get('', [SubscriberController::class, 'index']);
    Route::post('', [SubscriberController::class, 'store']);
    Route::get('{subscriber}', [SubscriberController::class, 'show']);
    Route::put('{subscriber}', [SubscriberController::class, 'update']);
    Route::delete('{subscriber}', [SubscriberController::class, 'destroy']);
});
