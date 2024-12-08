<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\Cart;
use App\Models\Api\CartItem;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function vnpayPayment(Request $request)
    {
        $vnp_Url = env('VNPAY_API_URL');
        $vnp_Returnurl = env('VNPAY_RETURN_URL');
        $vnp_TmnCode = env('VNPAY_MERCHANT_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');

        $order_id = $request->cart_id;
        $table_id = $request->table_id;
        $user_id = $request->user_id;
        $coupon_id = $request->coupon_id;
        $total_amount = $request->total_amount * 1000;
        $amount = $request->amount * 1000;
        $vnp_TxnRef = time();
        $vnp_OrderInfo = 'Thanh toán hóa đơn' . $vnp_TxnRef;
        $vnp_OrderType = 'HightFive Restaurant';
        $vnp_Amount = $amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';

        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array(
            'status' => true,
            'code' => '00',
            'message' => 'Redirect to VNPay',
            'payment_url' => $vnp_Url
        );

        Payment::create([
            'transaction_id' => $vnp_TxnRef,
            'provider_response' => $vnp_OrderInfo,
            'table_id' => $table_id,
            'order_id' => $order_id,
            'coupon_id' => $coupon_id,
            'payment_method' => $vnp_BankCode,
            'amount' => $amount,
            'total_amount' => $total_amount,
            'payment_date' =>  $inputData['vnp_CreateDate'],
            'user_id' => $user_id,
            // 'payment_status' => 'pending',
        ]);

        return response()->json($returnData);
    }



    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret =  env('VNPAY_HASH_SECRET');
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $vnp_TxnRef = $_GET['vnp_TxnRef'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $payment = Payment::where('transaction_id', $vnp_TxnRef)->first();

                if ($payment) {
                    $payment->payment_status = 'complete';
                    $payment->save();

                    $cart = Cart::find($payment->order_id);
                    if ($cart) {
                        $cart->status = 1;
                        $cart->save();

                        $order = Order::create([
                            'user_id' => $cart->user_id,
                            'table_id' => $cart->table_id,
                            'discount_promotion_id' => $payment->coupon_id,
                            'order_type' => 'dine_in',
                            'order_date' => $payment->payment_date,
                            'total_price' => $payment->total_amount,
                            'payment_status' => 'paid',
                            'status' => 'complete',
                            'note' => $cart->notes,
                        ]);

                        $cartItems = CartItem::where('cart_id', $cart->id)->get();
                        foreach ($cartItems as $cartItem) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'product_id' => $cartItem->product_id,
                                'quantity' => $cartItem->quantity,
                                'price' => $cartItem->product->price,
                            ]);
                        }

                        if ($payment->coupon_id)
                            CouponUser::create([
                                'user_id' => $payment->user_id,
                                'coupon_id' => $payment->coupon_id
                            ]);
                    }
                }

                return response()->json([
                    'status' => true,
                    'data' => [
                        'order' => $order,
                        'cartItems' => $cartItems,
                    ],
                    'message' => "Thanh toán thành công",
                ], 200);
            } else {
                $payment = Payment::where('transaction_id', $vnp_TxnRef)->first();

                if ($payment) {
                    $payment->payment_status = 'failed';
                    $payment->save();
                }

                $cart = Cart::find($payment->order_id);
                if ($cart) {
                    $order = Order::create([
                        'user_id' => $cart->user_id,
                        'table_id' => $cart->table_id,
                        'discount_promotion_id' => $payment->coupon_id,
                        'order_type' => 'dine_in',
                        'order_date' => $payment->payment_date,
                        'total_price' => $payment->total_amount,
                        'payment_status' => 'unpaid',
                        'status' => 'fails',
                        'note' => $cart->notes,
                    ]);

                    $cartItems = CartItem::where('cart_id', $cart->id)->get();
                    foreach ($cartItems as $cartItem) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $cartItem->product_id,
                            'quantity' => $cartItem->quantity,
                            'price' => $cartItem->product->price,
                        ]);
                    }
                }


                return response()->json([
                    "success" => false,
                    'data' => [
                        'order' => $order,
                        'cartItems' => $cartItems,
                    ],
                    'message' => "Thanh toán thất bại",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                'message' => "Chu ky khong hop le",
            ], 400);
        }
    }
}
