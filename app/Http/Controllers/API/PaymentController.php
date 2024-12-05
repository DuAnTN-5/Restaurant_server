<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Api\Cart;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function vnpayPayment(Request $request)
    {
        $vnp_Url = env('VNPAY_API_URL'); // URL của VNPay sandbox hoặc production
        $vnp_Returnurl = env('VNPAY_RETURN_URL'); // URL trả về sau khi thanh toán
        $vnp_TmnCode = env('VNPAY_MERCHANT_CODE'); // Mã website tại VNPay
        $vnp_HashSecret = env('VNPAY_HASH_SECRET'); // Chuỗi bí mật

        $vnp_TxnRef = uniqid('txn_'); // Mã giao dịch (unique)
        $vnp_OrderInfo = 'Order' . $vnp_TxnRef;  // Thông tin đơn hàng
        $cart_id = $request->cart_id;
        $user_id = $request->user_id;
        $amount = $request->amount * 1000;
        $vnp_Amount = $amount * 100; // Chuyển đổi từ VND sang cent (100)
        $vnp_Locale = 'vn'; // Mã ngôn ngữ (Việt Nam)
        $vnp_BankCode = 'NCB';

        // $vnp_BankCode = $request->bank_code;
        $vnp_IpAddr = $request->ip(); // Địa chỉ IP người dùng

        // Các tham số bắt buộc khác
        $inputData = array(
            "vnp_Version" => "2.1.0", // Phiên bản API
            "vnp_TmnCode" => $vnp_TmnCode, // Mã website
            "vnp_Amount" => $vnp_Amount, // Số tiền
            "vnp_Command" => "pay", // Lệnh thanh toán
            "vnp_CreateDate" => date('YmdHis'), // Thời gian tạo giao dịch
            "vnp_CurrCode" => "VND", // Mã tiền tệ
            "vnp_IpAddr" => $vnp_IpAddr, // Địa chỉ IP
            "vnp_Locale" => $vnp_Locale, // Ngôn ngữ
            "vnp_OrderInfo" => $vnp_OrderInfo, // Thông tin đơn hàng
            "vnp_OrderType" => "billpayment", // Loại đơn hàng (billpayment cho thanh toán hóa đơn)
            "vnp_ReturnUrl" => $vnp_Returnurl, // URL trả về
            "vnp_TxnRef" => $vnp_TxnRef, 
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp các tham số theo thứ tự từ A-Z
        ksort($inputData);

        // Tạo chuỗi query string
        $query = '';
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        // Loại bỏ dấu "&" ở cuối
        $query = rtrim($query, '&');

        // Thêm chuỗi bí mật vào cuối chuỗi query
        $vnp_SecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);

        // Thêm chữ ký vào query
        $url = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        // Log cho việc kiểm tra
        Log::info("VNPay Payment URL: " . $url);

        Payment::create([
            'transaction_id' => $vnp_TxnRef,
            'provider_response' => $vnp_OrderInfo,
            'table_id' => $cart_id,
            'payment_method' => $vnp_BankCode,
            'amount' => $amount,
            'payment_date' =>  $inputData['vnp_CreateDate'],
            'user_id' => $user_id,
            // 'payment_status' => 'pending',
        ]);

        // Chuyển hướng người dùng đến cổng thanh toán VNPay
        return response()->json([
            'status' => 'success',
            'message' => 'Redirect to VNPay',
            'payment_url' => $url,
            'cartID' => $cart_id,
            'transaction_code' => $vnp_TxnRef,
        ]);
    }



    public function vnpayCallback(Request $request)
    {
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $responseCode  = $request->input('vnp_ResponseCode');
        $txnRef = $request->input('vnp_TxnRef');
        // $cart_id = $request->input('cart_id');

        $inputData = [];

        // Lọc các tham số bắt đầu với 'vnp_'
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        // Xóa tham số 'vnp_SecureHash' khỏi danh sách inputData
        unset($inputData['vnp_SecureHash']);

        // Sắp xếp các tham số theo thứ tự từ A-Z
        ksort($inputData);

        // Tạo chuỗi hashData
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $vnp_HashSecret = env('VNPAY_HASH_SECRET'); // Lấy secret từ .env
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        // echo "<script>console.log('Response Code: " . $responseCode . "');</script>";

        if ($secureHash === $vnp_SecureHash) {
            if ($responseCode  == '00') {
                $payment = Payment::where('transaction_id', $txnRef)->first();
                if ($payment) {
                    $payment->payment_status = 'complete';
                    $payment->save();

                    // $cart = Cart::where('id', $cart_id)->first();
                    $cart = Cart::find($payment->table_id);
                    if ($cart) {
                        $cart->status = 1;
                        $cart->save();
                    }
                    return response()->json(['status' => 'success', 'message' => 'Thanh toán thành công'], 200);
                }
            } else {
                $payment = Payment::where('transaction_id', $txnRef)->first();
                if ($payment) {
                    $payment->payment_status = 'failed';
                    $payment->save();
                }

                // return view('test-payment-fails');
                return response()->json(['status' => 'error', 'message' => 'Thanh toán thất bại']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Dữ liệu giả mạo'], 400);
        }
    }







    // public function vnpayCallback(Request $request)
    // {
    //     $vnp_SecureHash = $request->input('vnp_SecureHash');
    //     $vnp_SecureHashType = $request->input('vnp_SecureHashType');
    //     $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType'); // Bỏ qua SecureHash khi xác thực

    //     // Tạo chuỗi hash từ các tham số, mã hóa và so sánh với giá trị SecureHash đã nhận từ VNPay
    //     ksort($inputData);
    //     $query = "";
    //     foreach ($inputData as $key => $value) {
    //         $query .= $key . "=" . $value . "&";
    //     }
    //     $query = rtrim($query, '&');
    //     $hashData = $query . "&vnp_SecureHashType=" . $vnp_SecureHashType;
    //     $secureHash = hash_hmac('sha512', $hashData, env('VNPAY_HASH_SECRET'));

    //     // So sánh giá trị SecureHash nhận được và giá trị SecureHash tính toán
    //     if ($secureHash === $vnp_SecureHash) {
    //         $txnRef = $request->input('vnp_TxnRef');  // Mã giao dịch
    //         // $orderInfo = $request->input('vnp_OrderInfo');  // Thông tin đơn hàng
    //         // $amount = $request->input('vnp_Amount');  // Số tiền thanh toán
    //         $responseCode  = $request->input('vnp_ResponseCode ');  // Mã phản hồi

    //         if ($responseCode  == '00') {
    //             $payment = Payment::where('transaction_id', $txnRef)->first();
    //             if ($payment) {
    //                 $payment->payment_status = 'complete';
    //                 $payment->save();
    //             }

    //             return response()->json(['status' => 'success', 'message' => 'Thanh toán thành công'], 200);
    //         } else {
    //             $payment = Payment::where('transaction_id', $txnRef)->first();
    //             if ($payment) {
    //                 $payment->payment_status = 'failed';
    //                 $payment->save();
    //             }

    //             return response()->json(['status' => 'error', 'message' => 'Thanh toán thất bại']);
    //         }
    //     } else {
    //         // Xử lý thanh toán thất bại
    //         // Log::error("SecureHash mismatch: " . json_encode($request->all()));
    //         return response()->json(['status' => 'error', 'message' => 'Dữ liệu giả mạo'], 400);
    //     }

    //     // return response()->json(['message' => 'Callback thất bại']);
    // }
}
