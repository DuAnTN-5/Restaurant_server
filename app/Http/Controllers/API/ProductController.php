<?php

namespace App\Http\Controllers\API;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Lấy danh sách tất cả sản phẩm
    public function index()
    {
        $products = Product::all();
        $products = $products->map(function($product) {
            $product->ingredients = $product->getIngredients();  
            return $product;
        });
        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($products),
        ]);
    }

    // Sản phẩm theo slug
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->ingredients = $product->getIngredients();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy món ăn.',
            ], 404);
        }

        // Tính sao trung bình và số lượt đánh giá
        $averageRating = $product->reviews()
            ->whereNotNull('rating')
            ->avg('rating');

        $totalRatings = $product->reviews()
            ->whereNotNull('rating')
            ->count();

        return response()->json([
            'status' => true,
            'data' => [
                'product' => new ProductResource($product),
                'average_rating' => number_format($averageRating, 1), // Định dạng 1 chữ số thập phân
                'total_ratings' => $totalRatings,
            ],
        ]);
    }
    

    public function latestProducts()
    {
        $productIds = [ 14, 16, 18, 17];
        $latestProducts = Product::whereIn('id', $productIds)->get();
        // $latestProducts = Product::latest('created_at')->take(4)->get();
        $latestProducts = $latestProducts->map(function($product) {
            $product->ingredients = $product->getIngredients();  
            return $product;
        });
        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($latestProducts),
        ]);
    }


    public function addToCart(Request $request)
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json([
                'status'=> false,
                'message' => 'Người dùng chưa đăng nhập.'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Tìm hoặc tạo giỏ hàng cho người dùng
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

         // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('cart_id', $cart->id)
                             ->where('product_id', $request->product_id)
                            ->first();

        if($cartItem) {
            // Nếu đã có, cập nhật số lượng
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có, thêm sản phẩm mới vào giỏ hàng
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $cart->load('cartItems.product') // Load các sản phẩm trong giỏ hàng
        ]);
    }  
    
    public function productCart(Request $request)
    {
        // Lấy tất cả dữ liệu JSON từ React gửi lên
        $data = $request->json()->all();

        $getProduct = [];
        foreach ($data as $key => $value) {
            // Tìm sản phẩm theo ID, nếu không tìm thấy trả về lỗi
            $get = Product::findOrFail($key)->toArray();

            // Thêm số lượng (qty) từ dữ liệu frontend
            $get['qty'] = $value;

            // Lưu vào danh sách sản phẩm
            $getProduct[] = $get;
        }

        // Trả về JSON response
        return response()->json([
            'response' => 'success',
            'data' => $getProduct,
        ], 200);
    }
}
