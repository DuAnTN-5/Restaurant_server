<?php

use App\Http\Controllers\API\PaymentController;
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
use App\Http\Controllers\Backend\DiscountPromotionsController;
use App\Http\Controllers\Backend\PaymentsController;
use App\Http\Controllers\Backend\PaymentMethodsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Backend\RoleDetailsController;
use App\Http\Controllers\Backend\RoleController;

Route::get('/districts/{province_id}', [LocationController::class, 'getDistricts']);
Route::get('/wards/{district_id}', [LocationController::class, 'getWards']);


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
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset'); // Hiển thị form đặt lại mật khẩu
});
// Định nghĩa route cho Admin Dashboard và Quản lý người dùng
Route::group(['middleware' => ['auth', 'admin']], function () {
    // Route index dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.index')->middleware('can:view dashboard'); // Sửa lại phần name thành 'admin.index'
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index'); // Danh sách người dùng
        Route::get('/users/create', 'create')->name('users.create'); // Form thêm người dùng
        Route::post('/users/store', 'store')->name('users.store'); // Lưu người dùng
        Route::get('/users/{user}/edit', 'edit')->name('users.edit'); // Form chỉnh sửa người dùng
        Route::put('/users/{user}', 'update')->name('users.update'); // Cập nhật người dùng

        // Route để xóa người dùng (soft delete)
        Route::delete('/users/{user}', 'destroy')->name('users.destroy'); // Xóa người dùng

        // Route để khôi phục người dùng đã xóa
        Route::post('/users/{user}/restore', 'restore')->name('users.restore'); // Khôi phục người dùng đã xóa

        // Route để xem danh sách người dùng đã bị xóa (soft deleted)
        Route::get('/users/trashed', 'trashed')->name('users.trashed'); // Xem danh sách người dùng đã xóa mềm
        Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

    })->middleware('can:manage users');
    Route::post('/store-location', [UserController::class, 'storeLocation'])->name('user.storeLocation');
    // Route để cập nhật trạng thái người dùng (active/inactive)
    Route::post('/users/update-status', [UserController::class, 'updateStatus'])->name('users.update-status')->middleware('can:manage users');

    // Routes cho nhân viên
    Route::prefix('/staff')->group(function () {

        Route::match(['get', 'post'], '/', [StaffController::class, 'index'])->name('staff.index'); // Danh sách nhân viên
        Route::post('/', [StaffController::class, 'store'])->name('staff.store'); // Lưu nhân viên mới
        Route::get('/{id}', [StaffController::class, 'show'])->name('staff.show'); // Xem chi tiết nhân viên
        // Route để hiển thị form cập nhật quyền cho nhân viên


    })->middleware('can:manage staff');
    //tạo quyền
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    //Route::get('/roles', [RoleController::class, 'roles.create']);
    Route::post('/roles/{role}/permissions/{permissions}', [RoleController::class, 'roles.update']);
    Route::put('/roles/{role}/permissions/toggle', [RoleController::class, 'togglePermission']);
    Route::delete('/roles/{role}/delete', [RoleController::class, 'destroy']);
    Route::put('/roles/{role}/rename', [RoleController::class, 'rename'])->name('roles.rename');

    // Route để cập nhật quyền
    Route::get('/role-details', [RoleDetailsController::class, 'index'])->name('roleDetail.index');
    Route::get('/role-details/create', [RoleDetailsController::class, 'create'])->name('roleDetail.create');
    Route::put('/role-details/{id}/update', [RoleDetailsController::class, 'updateStatus'])->name('roleDetail.updateStatus');


    Route::prefix('postCategories')->group(function () {
        Route::get('/', [PostCategoriesController::class, 'index'])->name('postCategories.index');
        Route::get('/create', [PostCategoriesController::class, 'create'])->name('postCategories.create');
        Route::post('/', [PostCategoriesController::class, 'store'])->name('postCategories.store');
        Route::get('/{id}/edit', [PostCategoriesController::class, 'edit'])->name('postCategories.edit');
        Route::put('/{id}', [PostCategoriesController::class, 'update'])->name('postCategories.update');
        Route::delete('/{id}', [PostCategoriesController::class, 'destroy'])->name('postCategories.destroy');
        Route::post('/update-status', [PostCategoriesController::class, 'updateStatus'])->name('postCategories.update-status');
    })->middleware('can:manage posts');

    // Route cho bài viết (Posts)
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index'); // Danh sách bài viết

        // Route để thêm bài viết
        Route::get('/create', [PostController::class, 'create'])->name('posts.create'); // Form thêm bài viết
        Route::post('/store', [PostController::class, 'store'])->name('posts.store'); // Lưu bài viết

        // Route để chỉnh sửa bài viết
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Form chỉnh sửa bài viết
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update'); // Cập nhật bài viết

        // Route để xóa bài viết (soft delete)
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); // Xóa bài viết

        // Route để khôi phục bài viết đã xóa
        Route::post('/{post}/restore', [PostController::class, 'restore'])->name('posts.restore'); // Khôi phục bài viết đã xóa

        // Route để xem danh sách bài viết đã bị xóa (soft deleted)
        Route::get('/trashed', [PostController::class, 'trashed'])->name('posts.trashed'); // Xem danh sách bài viết đã xóa mềm

        // Route để xóa vĩnh viễn bài viết
        Route::delete('/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.forceDelete'); // Xóa vĩnh viễn bài viết

        // Route cập nhật trạng thái (update-status) cho bài viết
        Route::post('/update-status', [PostController::class, 'updateStatus'])->name('posts.update-status');
    })->middleware('can:manage posts');


    Route::prefix('/categories/ProductCategories')->group(function () {
        Route::get('/', [ProductCategoriesController::class, 'index'])->name('ProductCategories.index');
        Route::get('/create', [ProductCategoriesController::class, 'create'])->name('ProductCategories.create');
        Route::post('/', [ProductCategoriesController::class, 'store'])->name('ProductCategories.store');
        Route::get('/{id}/edit', [ProductCategoriesController::class, 'edit'])->name('ProductCategories.edit');
        Route::put('/{id}', [ProductCategoriesController::class, 'update'])->name('ProductCategories.update');
        Route::delete('/{id}', [ProductCategoriesController::class, 'destroy'])->name('ProductCategories.destroy');
        Route::post('/update-status', [ProductCategoriesController::class, 'updateStatus'])->name('ProductCategories.update-status');

        // Route bổ sung
        Route::get('/trashed', [ProductCategoriesController::class, 'trashed'])->name('ProductCategories.trashed');
        Route::post('/restore/{id}', [ProductCategoriesController::class, 'restore'])->name('ProductCategories.restore');
        Route::delete('/force-delete/{id}', [ProductCategoriesController::class, 'forceDelete'])->name('ProductCategories.forceDelete');
    })->middleware('can:manage products');

    // Định nghĩa route cho quản lý sản phẩm (Products)
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/update-status', [ProductController::class, 'updateStatus'])->name('products.update-status');

        // Routes cho sản phẩm đã xóa mềm
        Route::get('/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
        Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
    })->middleware('can:manage products');


    Route::prefix('/tables')->group(function () {
        Route::get('/', [TableController::class, 'index'])->name('tables.index');          // Hiển thị danh sách bàn
        Route::get('/create', [TableController::class, 'create'])->name('tables.create');   // Hiển thị form thêm bàn mới
        Route::post('/', [TableController::class, 'store'])->name('tables.store');          // Lưu bàn mới
        Route::get('/{id}/edit', [TableController::class, 'edit'])->name('tables.edit');    // Hiển thị form chỉnh sửa bàn
        Route::put('/{id}', [TableController::class, 'update'])->name('tables.update');     // Cập nhật thông tin bàn
        Route::delete('/{id}', [TableController::class, 'destroy'])->name('tables.destroy');// Xóa bàn
        Route::post('/update-status', [TableController::class, 'updateStatus'])->name('tables.update-status');
    })->middleware('can:manage tables');
    // Định nghĩa route cho quản lý đặt chỗ (Reservations)
    Route::prefix('/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
        Route::post('/reservations/update-status', [ReservationController::class, 'updateStatus'])->name('reservations.update-status');
    })->middleware('can:manage tables');
    // Định nghĩa route cho quản lý đơn hàng (Orders)
    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::post('/', [OrdersController::class, 'store'])->name('orders.store');
        Route::get('/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
        Route::put('/{id}', [OrdersController::class, 'update'])->name('orders.update');
        Route::delete('/{id}', [OrdersController::class, 'destroy'])->name('orders.destroy');
        Route::post('/update-status', [OrdersController::class, 'updateStatus'])->name('orders.update-status');

        // Route cho modal "Đặt Món"
        Route::get('/{orderId}/items', [OrdersController::class, 'showItems'])->name('orders.items');
        Route::post('/{orderId}/items', [OrdersController::class, 'storeItems'])->name('orders.storeItems');
    })->middleware('can:manage orders');

    // CRUD cho Order Items chi tiết
    Route::prefix('/orders/{orderId}/manage-items')->group(function () {
        Route::get('/', [OrderItemsController::class, 'index'])->name('order_items.index');
        Route::get('/create', [OrderItemsController::class, 'create'])->name('order_items.create');
        Route::post('/', [OrderItemsController::class, 'store'])->name('order_items.store');
        Route::get('/{id}/edit', [OrderItemsController::class, 'edit'])->name('order_items.edit');
        Route::put('/{id}', [OrderItemsController::class, 'update'])->name('order_items.update');
        Route::delete('/{id}', [OrderItemsController::class, 'destroy'])->name('order_items.destroy');
    })->middleware('can:manage orders');
    Route::prefix('/coupons')->group(function () {
        Route::get('/', [CouponsController::class, 'index'])->name('coupons.index');           // Danh sách mã giảm giá
        Route::get('/create', [CouponsController::class, 'create'])->name('coupons.create');    // Form thêm mã giảm giá
        Route::post('/', [CouponsController::class, 'store'])->name('coupons.store');           // Lưu mã giảm giá
        Route::get('/{id}/edit', [CouponsController::class, 'edit'])->name('coupons.edit');     // Form chỉnh sửa mã giảm giá
        Route::put('/{id}', [CouponsController::class, 'update'])->name('coupons.update');      // Cập nhật mã giảm giá
        Route::delete('/{id}', [CouponsController::class, 'destroy'])->name('coupons.destroy'); // Xóa mã giảm giá

        // Route cập nhật trạng thái của mã giảm giá
        Route::post('/update-status', [CouponsController::class, 'updateStatus'])->name('coupons.update-status');
    })->middleware('can:manage coupons');

    Route::prefix('/payments')->group(function () {
        Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');           // Danh sách thanh toán
        Route::get('/create', [PaymentsController::class, 'create'])->name('payments.create');    // Form thêm thanh toán
        Route::post('/', [PaymentsController::class, 'store'])->name('payments.store');           // Lưu thanh toán
        Route::get('/{id}/edit', [PaymentsController::class, 'edit'])->name('payments.edit');     // Form chỉnh sửa thanh toán
        Route::put('/{id}', [PaymentsController::class, 'update'])->name('payments.update');      // Cập nhật thanh toán
        Route::delete('/{id}', [PaymentsController::class, 'destroy'])->name('payments.destroy'); // Xóa thanh toán

        // Route cập nhật trạng thái của thanh toán
        Route::post('/update-status', [PaymentsController::class, 'updateStatus'])->name('payments.update-status');
    })->middleware('can:manage payments');

    Route::prefix('/payment_methods')->group(function () {
        Route::get('/', [PaymentMethodsController::class, 'index'])->name('payment_methods.index');           // Danh sách phương thức thanh toán
        Route::get('/create', [PaymentMethodsController::class, 'create'])->name('payment_methods.create');    // Form thêm phương thức thanh toán
        Route::post('/', [PaymentMethodsController::class, 'store'])->name('payment_methods.store');           // Lưu phương thức thanh toán
        Route::get('/{id}/edit', [PaymentMethodsController::class, 'edit'])->name('payment_methods.edit');     // Form chỉnh sửa phương thức thanh toán
        Route::put('/{id}', [PaymentMethodsController::class, 'update'])->name('payment_methods.update');      // Cập nhật phương thức thanh toán
        Route::delete('/{id}', [PaymentMethodsController::class, 'destroy'])->name('payment_methods.destroy'); // Xóa phương thức thanh toán
        Route::post('/update-status', [PaymentMethodsController::class, 'updateStatus'])->name('payment_methods.update-status');

    })->middleware('can:manage payments');

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


Route::get('/testPayment', [PaymentController::class, 'vnpayCallback']);       


