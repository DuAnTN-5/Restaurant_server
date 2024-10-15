<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\PostCategoryController; 
use App\Http\Controllers\API\ProductCategoryController; 
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Đăng ký các route cho đăng ký và đăng nhập
Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

// Đường dẫn API yêu cầu xác thực
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [RegisterController::class, 'logout'])->name('logout');

    // Route cho sản phẩm
    Route::apiResource('products', ProductController::class);
    
    // Route cho bài viết
    Route::apiResource('posts', PostController::class);
    
    // Route cho danh mục bài viết
    Route::apiResource('post-categories', PostCategoryController::class);
    
    // Route cho danh mục sản phẩm
    Route::apiResource('product-categories', ProductCategoryController::class);

    // Route để lấy sản phẩm theo danh mục
    Route::get('product-categories/{id}/products', [ProductCategoryController::class, 'products'])->name('product-categories.products');
    
    // Đường dẫn để lấy thông tin người dùng hiện tại
    Route::get('user', function (Request $request) {
        return $request->user();
    })->name('user');
});
