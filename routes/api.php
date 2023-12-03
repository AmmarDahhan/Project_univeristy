<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pharmacy;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WarehouseOwner;

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
// Route Pharmacy
Route::post('/Register' , [Pharmacy::class , 'Register'])->middleware('Register');
Route::get('/login', [LoginController::class, 'Login'])->middleware(['checktoken']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/Add_order' , [Pharmacy::class , 'order']); // Query string and Request Body are required
Route::get('/show_orders' , [Pharmacy::class , 'show_orders']); // Query string is required
Route::get('/search_pharmacist' , [Pharmacy::class , 'search']); // Query string is required
Route::get('/category_show' , [Pharmacy::class ,'traverse' ]);
Route::get('/details' , [Pharmacy::class , 'details']); // Query string is required
// Route Warehouse Owner
Route::get('/Login' , [WarehouseOwner::class , 'Login']);
Route::post('/Add' , [WarehouseOwner::class , 'add_product']); // Request Body is required
Route::get('/search' , [WarehouseOwner::class , 'search']); // Query string is required
Route::get('/details' , [WarehouseOwner::class , 'details']); // query string is required
Route::post('/orders' , [WarehouseOwner::class , 'orderes_show']);
Route::post('/order_modify' , [WarehouseOwner::class , 'ordermodify']); // Query string and Request Body are required
