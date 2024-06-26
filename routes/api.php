<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//product
Route::get('/products', [ProductController::class, 'getAll']);
Route::get('/products/{id}', [ProductController::class, 'getProductsByCategory']);
Route::get('/product/{id}', [ProductController::class, 'getProductById']);
Route::get('/productByPrice/{min}/{max}', [ProductController::class, 'searchByPrice']);
Route::get('/productbyName/{name?}', [ProductController::class, 'getProductsByname']);
Route::get('/productwManuf/{id}', [ManufacturerController::class, 'ProductByManufacturer']);


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/manufacturers', [ManufacturerController::class, 'index']);

// auth
Route::post('/register', [AuthController::class, 'Register']);
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/reset-password-token', [AuthController::class, 'ResetPasswordToken']);
Route::post('/reset-password', [AuthController::class, 'ResetPassword']);

// need logged in
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'Logout']);
    Route::post('/createProduct', [ProductController::class, 'saveProduct']);
    Route::put('/modifyProduct/{id}', [ProductController::class, 'updateProduct']);
    Route::get('/getOrder', [OrderController::class, 'showOrderByuser']);
    Route::patch('/updateUser', [AuthController::class, 'UpdateUser']);
    //orders
    Route::post('/create-order', [OrderController::class, 'saveOrder']);
});


