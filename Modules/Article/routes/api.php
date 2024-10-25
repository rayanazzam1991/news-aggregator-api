<?php

use Illuminate\Support\Facades\Route;
use Modules\Article\Http\Controllers\ArticleController;
use Modules\Article\Http\Controllers\AuthorController;
use Modules\Article\Http\Controllers\CategoryController;
use Modules\Article\Http\Controllers\SourceController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {

    Route::group(['prefix' => 'articles'], function () {
        Route::post('/list', [ArticleController::class, 'list']);
        Route::get('/show/{id}', [ArticleController::class, 'show']);
    });

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('sources', SourceController::class);

});
