<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;

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

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/subcategories/{categoryId}', [CategoryController::class, 'getSubCategories']);
Route::get('/categories-tree', [CategoryController::class, 'getCategoriesTree']);
Route::get('/categories/{categoryId}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{categoryId}', [CategoryController::class, 'update']);
Route::delete('/categories/{categoryId}', [CategoryController::class, 'destroy']);
