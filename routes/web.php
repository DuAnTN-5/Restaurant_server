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
use App\Http\Controllers\Banking\OrderItemsController;
use App\Http\Controllers\Backend\CouponsController;
// test

// Route::get('/add-product', function () {
//     return view('test.add-product');
// });


// Route::get('/', function () {
//     return redirect()->route('login');
// });
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
});

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

// Định nghĩa route cho quản lý loại tin tức (Post Categories)
Route::prefix('/categories/post-categories')->group(function () {
    Route::get('/', [PostCategoriesController::class, 'index'])->name('PostCategories.index');
    Route::get('/create', [PostCategoriesController::class, 'create'])->name('PostCategories.create');
    Route::post('/', [PostCategoriesController::class, 'store'])->name('PostCategories.store');
    Route::get('/{id}/edit', [PostCategoriesController::class, 'edit'])->name('PostCategories.edit');
    Route::put('/{id}', [PostCategoriesController::class, 'update'])->name('PostCategories.update');
    Route::delete('/{id}', [PostCategoriesController::class, 'destroy'])->name('PostCategories.destroy');
    Route::post('/{id}/toggle-status', [PostCategoriesController::class, 'toggleStatus'])->name('PostCategories.toggleStatus');
});

// Định nghĩa route cho quản lý sản phẩm (Product Categories)
Route::prefix('/categories/product-categories')->group(function () {
    Route::get('/', [ProductCategoriesController::class, 'index'])->name('product-categories.index');
    Route::get('/create', [ProductCategoriesController::class, 'create'])->name('product-categories.create');
    Route::post('/', [ProductCategoriesController::class, 'store'])->name('product-categories.store');
    Route::get('/{id}/edit', [ProductCategoriesController::class, 'edit'])->name('product-categories.edit');
    Route::put('/{id}', [ProductCategoriesController::class, 'update'])->name('product-categories.update');
    Route::delete('/{id}', [ProductCategoriesController::class, 'destroy'])->name('product-categories.destroy');
    Route::post('{id}/toggle-status', [ProductCategoriesController::class, 'toggleStatus'])->name('product-categories.toggleStatus');
});

// Định nghĩa route cho quản lý tin tức (Posts)
Route::prefix('/posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Route để thay đổi trạng thái của bài viết
    Route::post('/{id}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggleStatus');
});
Route::prefix('/coupons')->group(function () {
    Route::get('/', [CouponsController::class, 'index'])->name('coupons.index'); // Hiển thị danh sách mã giảm giá
    Route::get('/create', [CouponsController::class, 'create'])->name('coupons.create'); // Form tạo mã giảm giá
    Route::post('/', [CouponsController::class, 'store'])->name('coupons.store'); // Lưu mã giảm giá
    Route::get('/{id}/edit', [CouponsController::class, 'edit'])->name('coupons.edit'); // Form chỉnh sửa mã giảm giá
    Route::put('/{id}', [CouponsController::class, 'update'])->name('coupons.update'); // Cập nhật mã giảm giá
    Route::delete('/{id}', [CouponsController::class, 'destroy'])->name('coupons.destroy'); // Xóa mềm mã giảm giá

    // Khôi phục mã giảm giá đã bị xóa mềm
    Route::post('/{id}/restore', [CouponsController::class, 'restore'])->name('coupons.restore');

    // Xóa vĩnh viễn mã giảm giá đã bị xóa mềm
    Route::delete('/{id}/force-delete', [CouponsController::class, 'forceDelete'])->name('coupons.forceDelete');

    // Cập nhật trạng thái của mã giảm giá (kích hoạt hoặc vô hiệu hóa)
    Route::post('/{id}/toggle-status', [CouponsController::class, 'updateStatus'])->name('coupons.updateStatus');
});

// Định nghĩa route cho quản lý sản phẩm (Products)
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Định nghĩa route cho quản lý bàn (Tables)
Route::prefix('/tables')->group(function () {
    Route::get('/', [TableController::class, 'index'])->name('tables.index');
    Route::post('/', [TableController::class, 'store'])->name('tables.store');
    Route::get('/{id}/edit', [TableController::class, 'edit'])->name('tables.edit');
    Route::put('/{id}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/{id}', [TableController::class, 'destroy'])->name('tables.destroy');
});

// Định nghĩa route cho quản lý đặt chỗ (Reservations)
Route::prefix('/reservations')->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});
// Định nghĩa route cho quản lý đơn hàng (Orders)
Route::prefix('/orders')->group(function () {
    Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/{id}/toggle-status', [OrderController::class, 'toggleStatus'])->name('orders.toggleStatus');
});

// Định nghĩa route cho quản lý mặt hàng trong đơn (Order Items)
Route::prefix('/order-items')->group(function () {
    Route::get('/{order_id}', [OrderItemController::class, 'index'])->name('order-items.index');
    Route::post('/{order_id}/store', [OrderItemController::class, 'store'])->name('order-items.store');
    Route::get('/{id}/edit', [OrderItemController::class, 'edit'])->name('order-items.edit');
    Route::put('/{id}', [OrderItemController::class, 'update'])->name('order-items.update');
    Route::delete('/{id}', [OrderItemsController::class, 'destroy'])->name('order-items.destroy');
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


// Định nghĩa route cho thanh toán PayPal
// Route::prefix('payment')->group(function () {
//     Route::get('/create/{reservationId}/{amount}', [PayPalController::class, 'createPayment'])->name('payment.create');
//     Route::get('/success', [PayPalController::class, 'executePayment'])->name('payment.success');
//     Route::get('/cancel', [PayPalController::class, 'cancelPayment'])->name('payment.cancel');
// });