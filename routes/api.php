<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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
    Route::post("login",[AuthController::class,'login']);
});

//Product controller
Route::prefix("products")->group(function (){
    Route::get("",[ProductController::class,'getAllProducts']);
    Route::get("/{id}",[ProductController::class,'getProductById']);
    Route::put("/{id}",[ProductController::class,'updateProduct']);
    Route::delete("/{id}",[ProductController::class,'deleteProduct']);
    Route::post("",[ProductController::class,'createProduct']);
});
Route::get('manufacturers',[ProductController::class,'getAllManufacturers']);
Route::get('search',[ProductController::class,'searchProduct']);
Route::prefix("carts")->group(function (){
    Route::middleware('auth:api')->group(
      function (){
          Route::get("",[CartController::class,'getAllCarts']);
//          Route::get("/{id}",[CartController::class,'getProductById']);
          Route::put("/{id}",[CartController::class,'updateProduct']);
          Route::delete("/{product_id}",[CartController::class,'deleteCart']);
          Route::post("",[CartController::class,'addToCarts']);
      }
    );
});
//order controller
Route::prefix("orders")->group(function (){
    Route::middleware('auth:api')->group(
        function (){
            Route::get("",[\App\Http\Controllers\OrderController::class,'getAllOrders']);
            Route::get("/{id}",[\App\Http\Controllers\OrderController::class,'getOrderByOderId']);
            Route::put("/{id}",[CartController::class,'updateProduct']);
            Route::delete("/{product_id}",[CartController::class,'deleteCart']);
            Route::post("",[\App\Http\Controllers\OrderController::class,'offlinePayment']);
            Route::post("/{id}",[\App\Http\Controllers\OrderController::class,'onlinePayment']);
        }
    );
});
Route::prefix("online")->group( function (){
    Route::post("",[\App\Http\Controllers\OrderController::class,'updateInfoOrder'])->middleware('auth:api');
});

Route::get('success',function (Request $request){
    return response()->json("success banking",200);
});
Route::get('banking',[\App\Http\Controllers\OrderController::class,'onlinePayment'])->middleware('auth:api');
