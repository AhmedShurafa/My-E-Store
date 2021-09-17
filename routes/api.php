<?php

use App\Http\Controllers\Api\AuthTokensController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('auth/tokens',[AuthTokensController::class,'store']);
Route::delete('auth/logout',[AuthTokensController::class,'destroy']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories','Api\CategoriesController');
    Route::apiResource('products','Api\ProductsController');
});

