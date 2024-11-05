<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\PostCategoriesController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReservationController;
use App\Http\Controllers\Backend\TableController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\OrdersController;
use App\Http\Controllers\Backend\OrderItemsController; 


use App\Http\Controllers\Backend\CouponsController;

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
Route::group(['middleware' => ['auth', 'admin']], function () {
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

        // Route để khôi phục người dùng đã xóa
        Route::post('/users/{user}/restore', 'restore')->name('users.restore'); // Khôi phục người dùng

        // Route để xem chi tiết người dùng
        Route::get('/users/{user}', 'show')->name('users.show'); // Xem chi tiết người dùng
    });

    // Route để cập nhật trạng thái người dùng
    Route::post('/users/update-status', [UserController::class, 'updateStatus'])->name('users.update-status'); // Bỏ /admin trong URL
    // Routes cho nhân viên
    Route::prefix('/staff')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('staff.index'); // Danh sách nhân viên
        Route::get('/create', [StaffController::class, 'create'])->name('staff.create'); // Form thêm nhân viên
        Route::post('/', [StaffController::class, 'store'])->name('staff.store'); // Lưu nhân viên mới
        Route::get('/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit'); // Form chỉnh sửa nhân viên
        Route::put('/{id}', [StaffController::class, 'update'])->name('staff.update'); // Cập nhật thông tin nhân viên
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('staff.destroy'); // Xóa nhân viên

        // Route để khôi phục nhân viên đã xóa
        Route::post('/{id}/restore', [StaffController::class, 'restore'])->name('staff.restore'); // Khôi phục nhân viên

        // Route để xem chi tiết nhân viên
        Route::get('/{id}', [StaffController::class, 'show'])->name('staff.show'); // Xem chi tiết nhân viên
    });

    // Route cho danh mục bài viết (Post Categories)
    Route::prefix('categories/post-categories')->group(function () {
        Route::get('/', [PostCategoriesController::class, 'index'])->name('post-categories.index'); // Danh sách danh mục bài viết
        Route::get('/create', [PostCategoriesController::class, 'create'])->name('post-categories.create'); // Form thêm danh mục bài viết
        Route::post('/', [PostCategoriesController::class, 'store'])->name('post-categories.store'); // Lưu danh mục bài viết
        Route::get('/{id}/edit', [PostCategoriesController::class, 'edit'])->name('post-categories.edit'); // Form chỉnh sửa danh mục bài viết
        Route::put('/{id}', [PostCategoriesController::class, 'update'])->name('post-categories.update'); // Cập nhật danh mục bài viết
        Route::delete('/{id}', [PostCategoriesController::class, 'destroy'])->name('post-categories.destroy'); // Xóa danh mục bài viết

        // Route cập nhật trạng thái (update-status) cho danh mục bài viết
        Route::post('/post-categories/update-status', [PostCategoriesController::class, 'updateStatus'])->name('post-categories.update-status');
    });

    // Route cho bài viết (Posts)
    Route::prefix('/posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index'); // Danh sách bài viết
        Route::get('/create', [PostController::class, 'create'])->name('posts.create'); // Form thêm bài viết
        Route::post('/', [PostController::class, 'store'])->name('posts.store'); // Lưu bài viết
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Form chỉnh sửa bài viết
        Route::put('/{id}', [PostController::class, 'update'])->name('posts.update'); // Cập nhật bài viết
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy'); // Xóa bài viết

        // Route cập nhật trạng thái (update-status) cho bài viết
        Route::post('/posts/update-status', [PostController::class, 'updateStatus'])->name('posts.update-status');
    });

    // Định nghĩa route cho quản lý sản phẩm (Product Categories)
    Route::prefix('/categories/product-categories')->group(function () {
        Route::get('/', [ProductCategoriesController::class, 'index'])->name('product-categories.index');
        Route::get('/create', [ProductCategoriesController::class, 'create'])->name('product-categories.create');
        Route::post('/', [ProductCategoriesController::class, 'store'])->name('product-categories.store');
        Route::get('/{id}/edit', [ProductCategoriesController::class, 'edit'])->name('product-categories.edit');
        Route::put('/{id}', [ProductCategoriesController::class, 'update'])->name('product-categories.update');
        Route::delete('/{id}', [ProductCategoriesController::class, 'destroy'])->name('product-categories.destroy');
        Route::post('/product-categories/update-status', [ProductCategoriesController::class, 'updateStatus'])->name('product-categories.update-status');

    });
    // Định nghĩa route cho quản lý sản phẩm (Products)
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/update-status', [ProductController::class, 'updateStatus'])->name('products.update-status');
    });

    Route::prefix('/tables')->group(function () {
        Route::get('/', [TableController::class, 'index'])->name('tables.index');          // Hiển thị danh sách bàn
        Route::get('/create', [TableController::class, 'create'])->name('tables.create');   // Hiển thị form thêm bàn mới
        Route::post('/', [TableController::class, 'store'])->name('tables.store');          // Lưu bàn mới
        Route::get('/{id}/edit', [TableController::class, 'edit'])->name('tables.edit');    // Hiển thị form chỉnh sửa bàn
        Route::put('/{id}', [TableController::class, 'update'])->name('tables.update');     // Cập nhật thông tin bàn
        Route::delete('/{id}', [TableController::class, 'destroy'])->name('tables.destroy');// Xóa bàn
        Route::post('/update-status', [TableController::class, 'updateStatus'])->name('tables.update-status');
    });
    // Định nghĩa route cho quản lý đặt chỗ (Reservations)
    Route::prefix('/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
        Route::post('/reservations/update-status', [ReservationController::class, 'updateStatus'])->name('reservations.update-status');
    });
    // Định nghĩa route cho quản lý đơn hàng (Orders)
    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::post('/', [OrdersController::class, 'store'])->name('orders.store');
        Route::get('/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
        Route::put('/{id}', [OrdersController::class, 'update'])->name('orders.update');
        Route::delete('/{id}', [OrdersController::class, 'destroy'])->name('orders.destroy');
        Route::post('/update-status', [OrdersController::class, 'updateStatus'])->name('orders.updateStatus');
        
        // Route cho modal "Đặt Món"
        Route::get('/{orderId}/items', [OrdersController::class, 'showItems'])->name('orders.items');
        Route::post('/{orderId}/items', [OrdersController::class, 'storeItems'])->name('orders.storeItems');
    });
    
    // CRUD cho Order Items chi tiết
    Route::prefix('/orders/{orderId}/manage-items')->group(function () {
        Route::get('/', [OrderItemsController::class, 'index'])->name('order_items.index');
        Route::get('/create', [OrderItemsController::class, 'create'])->name('order_items.create');
        Route::post('/', [OrderItemsController::class, 'store'])->name('order_items.store');
        Route::get('/{id}/edit', [OrderItemsController::class, 'edit'])->name('order_items.edit');
        Route::put('/{id}', [OrderItemsController::class, 'update'])->name('order_items.update');
        Route::delete('/{id}', [OrderItemsController::class, 'destroy'])->name('order_items.destroy');
    });
    
    
    
    
});


// Facebook Authentication routes
Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

// Google Authentication routes
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});
Route::get('language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'vi'])) {
        session(['app_locale' => $lang]);
    }
    return redirect()->back();
});
