<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//AuthController
Route::prefix("auth")->group(function (){
    Route::post("register",[AuthController::class,'registerUser']);
});

//Product controller
Route::prefix("products")->group(function (){
    Route::get("",[\App\Http\Controllers\ProductController::class,'getAllProducts']);
    Route::get("/{id}",[\App\Http\Controllers\ProductController::class,'getProductById']);
    Route::put("/{id}",[\App\Http\Controllers\ProductController::class,'updateProduct']);
    Route::delete("/{id}",[\App\Http\Controllers\ProductController::class,'deleteProduct']);
    Route::post("",[\App\Http\Controllers\ProductController::class,'createProduct']);
});
