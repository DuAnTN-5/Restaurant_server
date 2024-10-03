<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\PostCategoriesController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReservationController;
use App\Http\Controllers\Backend\TableController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login'); // Hiển thị form đăng nhập
    Route::post('/login', 'login')->name('login');        // Xử lý đăng nhập
    Route::post('/logout', 'logout')->name('logout');     // Xử lý đăng xuất
});
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register'); // Hiển thị form đăng ký
    Route::post('/register', 'register')->name('register');            // Xử lý đăng ký
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('forgotPassword'); // Hiển thị form yêu cầu đặt lại mật khẩu
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email'); // Xử lý gửi email đặt lại mật khẩu
});
// Định nghĩa route cho Admin Dashboard và Quản lý người dùng
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
    // Route index dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.index'); // Sửa lại phần name thành 'admin.index'

    // User Management Routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index'); // Danh sách người dùng
        Route::get('/users/create', 'create')->name('users.create'); // Form thêm người dùng
        Route::post('/users/store', 'store')->name('users.store'); // Lưu người dùng
        Route::get('/users/{user}/edit', 'edit')->name('users.edit'); // Form chỉnh sửa người dùng
        Route::put('/users/{user}', 'update')->name('users.update'); // Cập nhật người dùng
        Route::delete('/users/{user}', 'destroy')->name('users.destroy'); // Xóa người dùng
    });

    // Route để cập nhật trạng thái người dùng
    Route::post('/users/update-status', [UserController::class, 'updateStatus'])->name('users.update-status'); // Bỏ /admin trong URL
});

// Định nghĩa route cho quản lý loại tin tức (Post Categories)
Route::prefix('admin/categories/post-categories')->group(function () {
    Route::get('/', [PostCategoriesController::class, 'index'])->name('PostCategories.index');
    Route::get('/create', [PostCategoriesController::class, 'create'])->name('PostCategories.create');
    Route::post('/', [PostCategoriesController::class, 'store'])->name('PostCategories.store');
    Route::get('/{id}/edit', [PostCategoriesController::class, 'edit'])->name('PostCategories.edit');
    Route::put('/{id}', [PostCategoriesController::class, 'update'])->name('PostCategories.update');
    Route::delete('/{id}', [PostCategoriesController::class, 'destroy'])->name('PostCategories.destroy');
    Route::post('/{id}/toggle-status', [PostCategoriesController::class, 'toggleStatus'])->name('PostCategories.toggleStatus');
});

// Định nghĩa route cho quản lý sản phẩm (Product Categories)
Route::prefix('admin/categories/product-categories')->group(function () {
    Route::get('/', [ProductCategoriesController::class, 'index'])->name('product-categories.index');
    Route::get('/create', [ProductCategoriesController::class, 'create'])->name('product-categories.create');
    Route::post('/', [ProductCategoriesController::class, 'store'])->name('product-categories.store');
    Route::get('/{id}/edit', [ProductCategoriesController::class, 'edit'])->name('product-categories.edit');
    Route::put('/{id}', [ProductCategoriesController::class, 'update'])->name('product-categories.update');
    Route::delete('/{id}', [ProductCategoriesController::class, 'destroy'])->name('product-categories.destroy');
    Route::post('{id}/toggle-status', [ProductCategoriesController::class, 'toggleStatus'])->name('product-categories.toggleStatus');
});

// Định nghĩa route cho quản lý tin tức (Posts)
Route::prefix('admin/posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Route để thay đổi trạng thái của bài viết
    Route::post('/{id}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
});


// Định nghĩa route cho quản lý sản phẩm (Products)
Route::prefix('admin/products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Định nghĩa route cho quản lý bàn (Tables)
Route::prefix('admin/tables')->group(function () {
    Route::get('/', [TableController::class, 'index'])->name('tables.index');
    Route::post('/', [TableController::class, 'store'])->name('tables.store');
    Route::get('/{id}/edit', [TableController::class, 'edit'])->name('tables.edit');
    Route::put('/{id}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/{id}', [TableController::class, 'destroy'])->name('tables.destroy');
});

// Định nghĩa route cho quản lý đặt chỗ (Reservations)
Route::prefix('admin/reservations')->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// Định nghĩa route cho Facebook Auth
Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

// Định nghĩa route cho thanh toán PayPal
Route::prefix('payment')->group(function () {
    Route::get('/create/{reservationId}/{amount}', [PayPalController::class, 'createPayment'])->name('payment.create');
    Route::get('/success', [PayPalController::class, 'executePayment'])->name('payment.success');
    Route::get('/cancel', [PayPalController::class, 'cancelPayment'])->name('payment.cancel');
});
