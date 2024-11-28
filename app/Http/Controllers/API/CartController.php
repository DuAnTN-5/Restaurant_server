<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\Cart;
use App\Models\Api\CartItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index($id)
    {
        $cart = Cart::where('user_id', '=', $id)->get();
        return response()->json($cart);
    }

    public function listProduct($cartId)
    {
        $listProduct = CartItem::where('cart_id', '=', $cartId)
            ->with('product')
            ->get();

        if ($listProduct->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Giỏ hàng không có sản phẩm nào.',
            ]);
        }

        $productsDetails = $listProduct->map(function ($cartItem) {
            return [
                'product_name' => $cartItem->product->name,
                'product_image' => $cartItem->product->image_url,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ];
        });

        // Trả về phản hồi
        return response()->json([
            'status' => true,
            'message' => 'Danh sách món ăn trong giỏ hàng.',
            'data' => $productsDetails
        ]);
    }

    public function addCart(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "user_id" => 'required',
            "table_id" => 'required',
            "date" => 'required',
            "time" => 'required',
            "guest_count" => 'required',
            "notes" => 'nullable',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi thông tin không xác thực',
            ]);
        }

        $addCart = Cart::create($validated);
        //đổi trạng thái bàn
        $table_id = $request->table_id;
        $table = Table::find($table_id);
        if ($table) {
            $table->update([
                'status' => 'reserved'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bàn không tồn tại',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tạo thành công',
            'data' => $addCart

        ], 201);
    }

    public function addProduct(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "cart_id" => 'required',
            "product_id" => 'required',
            "quantity" => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi thông tin không xác thực' . $validated->errors()->first(),
            ]);

            $cartId = $request->cart_id;
            $productId = $request->product_id;
            $quantity = $request->quantity;

            $cartItem = CartItem::where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Sản phẩm đã được cập nhật vào giỏ hàng.',
                    'data' => $cartItem
                ]);
            } else {
                $addProduct = CartItem::create([
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
                    'data' => $addProduct
                ]);
            }
        }
    }

    public function quantityUp(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "cart_id" => 'required',
            "product_id" => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi xác thực' . $validated->errors(),
            ]);
        }

        $quantity = CartItem::where('cart_id', '=', $request->cart_id)
            ->where('product_id', '=', $request->product_id)->first();

        if ($quantity) {
            $quantity->update(['quantity' => $quantity->quantity++]);
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật số lượng thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật số lượng thất bại' . $validated->errors(),
            ]);
        }
    }

    public function quantityDown(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "cart_id" => 'required',
            "product_id" => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi xác thực' . $validated->errors(),
            ]);
        }

        $quantity = CartItem::where('cart_id', '=', $request->cart_id)
            ->where('product_id', '=', $request->product_id)->first();

        if ($quantity) {
            $quantity->update(['quantity' => $quantity->quantity--]);
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật số lượng thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật số lượng thất bại' . $validated->errors(),
            ]);
        }
    }

    public function destroyProduct(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "cart_id" => 'required',
            "product_id" => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi xác thực' . $validated->errors(),
            ]);
        }

        $product = CartItem::where('cart_id', '=', $request->cart_id)
            ->where('product_id', '=', $request->product_id)->first();

        if ($product) {
            $product->delete();
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa món ăn thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy món ăn' . $validated->errors(),
            ]);
        }
    }

    public function destroyCart($id)
    {

        $cart = Cart::find($id);

        if ($cart) {
            $cart->delete();
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa giỏ hàng thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy giỏ hàng',
            ]);
        }
    }
}
