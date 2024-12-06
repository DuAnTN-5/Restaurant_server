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
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        // $vnp_Returnurl = "http://127.0.0.1:8000/api/vnpay-return";
        $vnp_Returnurl = "http://localhost:5173/payment";

        $vnp_TmnCode = "C2RZR4BT"; //Mã website tại VNPAY
        $vnp_HashSecret = "VG80KWGXXSM836B2746P9XQZYCOD33QJ"; //Chuỗi bí mật

        $vnp_TxnRef = time(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'thanh toán hóa đơn';
        $vnp_OrderType = 'HightFive Restaurant';
        $vnp_Amount = 10000 * 100;
        $vnp_Locale = 'vn';

        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version

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
            'success' => true,
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );

        return response()->json($returnData);
// viết request
        // Payment::create([
        //     'transaction_id' => $vnp_TxnRef,
        //     'provider_response' => $vnp_OrderInfo,
        //     'table_id' => $cart_id,
        //     'payment_method' => $vnp_BankCode,
        //     'amount' => $amount,
        //     'payment_date' =>  $inputData['vnp_CreateDate'],
        //     'user_id' => $user_id,
        //      'payment_status' => 'pending',
        // ]);

        // Chuyển hướng người dùng đến cổng thanh toán VNPay
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Redirect to VNPay',
        //     'payment_url' => $url,
        //     'cartID' => $cart_id,
        //     'transaction_code' => $vnp_TxnRef,
        // ]);
       
    }



    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret =  env('VNPAY_HASH_SECRET'); 
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
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
                

                    return response()->json([
                        'success' => true,
                        'message'=> "GD Thanh cong",
                        
                    ]);
                } 
                else {
                    
                    return response()->json([
                        "success"=> false,
                        'message'=> "GD Khong thanh cong",
                        
                    ]);
                    }
            } else {
                
                return response()->json([
                    "success"=> false,
                    'message'=> "Chu ky khong hop le",
                    
                ]);
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
