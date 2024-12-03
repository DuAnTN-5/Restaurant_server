<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\Cart;
use App\Models\Api\CartItem;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index($id)
    {
        // Lấy tất cả giỏ hàng của người dùng với ID $id
        $cart = Cart::where('user_id', '=', $id)->get();

        // Tính toán count và total cho mỗi giỏ hàng
        $cartList = $cart->map(function ($cartItem) {
            // Lấy tất cả CartItem của giỏ hàng hiện tại
            $cartItems = CartItem::where('cart_id', '=', $cartItem->id)->get();

            // Tính số lượng sản phẩm trong giỏ
            $countProduct = $cartItems->count();

            // Tính tổng tiền cho giỏ hàng
            $total = 0;
            foreach ($cartItems as $cartItemDetail) {
                $quantity = $cartItemDetail->quantity;
                $price = $this->itemTotal($cartItemDetail->product_id); // Giả sử itemTotal tính toán tổng giá trị của sản phẩm
                $total += ($quantity * $price);
            }

            // Trả về dữ liệu của giỏ hàng với count và total

            return (object) [
                'id' => $cartItem->id,
                'user_id' => $cartItem->user_id,
                'table_id' => $cartItem->table_id,
                'date' => $cartItem->date,
                'time' => $cartItem->time,
                'guest_count' => $cartItem->guest_count,
                'notes' => $cartItem->notes,
                'count' => $countProduct,
                'total' => $total,
            ];
        });

        // Trả về phản hồi JSON với thông tin giỏ hàng
        return response()->json([
            'status' => true,
            'message' => 'Danh sách giỏ hàng',
            'data' => $cartList
        ]);
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
                'id' => $cartItem->product_id,
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

        // $checkCart = Cart::where('user_id', '=', $request->user_id)
        //     ->where('table_id', '=', $request->table_id);
        $checkCart = Cart::where('user_id', '=', $request->user_id)
            ->where('table_id', '=', $request->table_id)
            ->where('date', '=', $request->date)
            ->first();

        if (!$checkCart) {
            $addCart = Cart::create([
                "user_id" => $request->user_id,
                "table_id" => $request->table_id,
                "date" => $request->date,
                "time" => $request->time,
                "guest_count" => $request->guest_count,
                "notes" => $request->notes,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Tạo thành công',
                'data' => $addCart

            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bàn đã tồn tại.',
            ]);
        }
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
        }
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

    public function quantityUp($itemId, $tableId)
    {
        $quantity = CartItem::where('product_id', $itemId)
            ->where('cart_id', $tableId)
            ->first();

        if ($quantity) {
            $quantity->update(['quantity' => $quantity->quantity + 1]);
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật số lượng thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật số lượng thất bại',
            ]);
        }
    }
    public function quantityDown($itemId, $tableId)
    {
        $quantity = CartItem::where('product_id', $itemId)
            ->where('cart_id', $tableId)
            ->first();

        if ($quantity) {
            if ($quantity->quantity > 1) {
                $quantity->update(['quantity' => $quantity->quantity - 1]);
                return response()->json([
                    'status' => true,
                    'message' => 'Cập nhật số lượng thành công',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Số lượng không thể giảm dưới 1',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật số lượng thất bại',
            ]);
        }
    }


    public function destroyProduct($itemId, $tableId)
    {
        $product = CartItem::where('product_id', $itemId)
            ->where('cart_id', $tableId)
            ->first();

        if ($product) {
            $product->delete();
            return response()->json([
                'status' => true,
                'message' => 'Đã xóa món ăn thành công',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy món ăn',
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

    public function itemTotal($productId)
    {
        $item = Product::find($productId);

        $price = $item->price;


        return $price;
    }
}
