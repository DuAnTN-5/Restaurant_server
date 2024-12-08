<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CommentPostController;
use App\Http\Controllers\API\CommentProductController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\CouponsController;
use App\Http\Controllers\API\DishReviewController;
use App\Http\Controllers\API\FacebookController;
use App\Http\Controllers\API\GoogleController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\PostCategoryController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\SocialAuthController;
use App\Http\Controllers\API\TableController;
use App\Models\DishReview;
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

// Đường dẫn API yêu cầu xác thực
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [RegisterController::class, 'logout']);

    Route::post('change-password', [RegisterController::class, 'changePassword']);
    Route::post('update-user-info', [RegisterController::class, 'updateUserInfo']);

    Route::post('/ratings', [ReviewController::class, 'rate']);
    Route::post('/product-comments', [ReviewController::class, 'comment']);
    // Thêm bình luận mới
    Route::post('/post-comments', [CommentPostController::class, 'store']);

    // Bàn
    Route::get('/tables', [TableController::class, 'index']);

    Route::get('/cart/list-product/{cartId}', [CartController::class, 'listProduct']);
    Route::post('/cart/add-cart', [CartController::class, 'addCart']);
    Route::post('/cart/add-product', [CartController::class, 'addProduct']);
    Route::post('/cart/quantity-up/{itemId}/{tableId}', [CartController::class, 'quantityUp']);
    Route::post('/cart/quantity-down/{itemId}/{tableId}', [CartController::class, 'quantityDown']);
    Route::post('/cart/delete/{itemId}/{tableId}', [CartController::class, 'destroyProduct']);
    Route::post('/cart/{id}/delete', [CartController::class, 'destroyCart']);
    Route::get('/cart/list/{id}', [CartController::class, 'index']);
    Route::get('/paid-carts/{id}', [CartController::class, 'getPaidCarts']);

    Route::post('/product/cart', [ProductController::class, 'productCart']);

    Route::post('/check-coupon', [CouponsController::class, 'checkCoupon']);

    Route::post('/vnpay/payment', [PaymentController::class, 'vnpayPayment']);
    Route::get('/vnpay/callback', [PaymentController::class, 'vnpayCallback']);

    Route::post('/contact', [ContactController::class, 'sendContact']);
});

// Đăng ký các route cho đăng ký và đăng nhập
Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

// Route xác nhận email đăng kí
Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail']);
// Route quên mật khẩu
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password', [RegisterController::class, 'resetPassword']);

Route::prefix('auth')->group(function () {
    // Google Login
    Route::get('google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('google/callback', [GoogleController::class, 'handleGoogleCallback']);
    
    // Facebook Login
    Route::get('facebook', [FacebookController::class, 'redirectToFacebook']);
    Route::get('facebook/callback', [FacebookController::class, 'handleFacebookCallback']);
});

// Route cho sản phẩm
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
//bình luận của 1 món ăn
Route::get('/products/{product_id}/comments', [ReviewController::class, 'getComments']);
// Route::get('/products/{slug}/comments', [CommentProductController::class, 'index']);
Route::get('/latestProducts', [ProductController::class, 'latestProducts']);
// Route cho danh mục sản phẩm
Route::get('/product-categories', [ProductCategoryController::class, 'index']);

// Route để lấy sản phẩm theo danh mục
Route::get('/product-categories/{id}', [ProductCategoryController::class, 'products']);



// Route cho bài viết
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

// bình luận của 1 bài viết
Route::get('/posts/{postId}/comments', [CommentPostController::class, 'index']);

Route::get('/coupons', [CouponsController::class, 'getCoupons']);
// Route::get('coupons/{code}', [CouponsController::class, 'getCouponByCode']);

// Route::post('/use-coupon', [CouponsController::class, 'useCoupon']);



Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
})->name('user');
