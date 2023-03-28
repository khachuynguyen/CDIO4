<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//order
Route::prefix('orders')->group(
    function (){
        Route::get('',);
        Route::post('',[\App\Http\Controllers\OrderController::class,'onlinePayment']);
    }
)->middleware('auth');
//carts
Route::prefix('carts')->group(
    function (){
        Route::get('',[\App\Http\Controllers\CartController::class,'getCarts']);
    }
)->middleware('auth');
Route::prefix('login')->group(function (){
    Route::get('',function (){
        return view('login');
    });
    Route::post('',[\App\Http\Controllers\AuthController::class, 'doLogin']);
}
);
Route::get('logout', function (){
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
});



