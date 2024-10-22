<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Client\ClientUserController;
use App\Models\ProductCapacity;
use App\Models\ProductColor;

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

// Auth
    Route::post("register", [AuthController::class, 'register']);
    Route::post("login", [AuthController::class, 'login']);
    Route::post("logout", [AuthController::class, 'logout'])->middleware("auth:sanctum");
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])
        ->name('password.reset');

// Admin
    Route::apiResource("admin/catalogue", CatalogueController::class);
    Route::apiResource("admin/user", UserController::class);
    Route::apiResource("admin/producCapacity", ProductCapacity::class);
    Route::apiResource("admin/productColor", ProductColor::class);
    Route::apiResource("admin/banner", BannerController::class);
    Route::apiResource('admin/products', ProductController::class);


// Client
    // Route::middleware('auth:sanctum')->put('/user/{id}', [ClientUserController::class, 'updateUserInfo']);
    Route::put('/user/{id}', [ClientUserController::class, 'updateUserInfo']);
    Route::put('/user/{id}/password', [ClientUserController::class, 'updatePassword']);
    Route::get('/product/{slug}',[\App\Http\Controllers\Client\ProductController::class, 'productDetail'])->name('product.detail');