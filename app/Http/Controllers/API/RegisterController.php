<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends BaseController
{
     // Register api
     public function register(Request $request): JsonResponse
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required',
             'email' => 'required|email|unique:users,email',
             'password' => 'required',
             'c_password' => 'required|same:password',
         ]);
 
         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors());
         }
 
         $input = $request->all();
         $input['password'] = bcrypt($input['password']);

          // Tạo mã xác nhận và thời gian hết hạn
          $verificationToken = Str::random(64);
          $input["verification_token"] = $verificationToken;
          $input["verification_expires_at"] = now()->addMinute(30);

         $user = User::create($input);
        //  $success['token'] =  $user->createToken('MyApp')->plainTextToken;

         $verificationUrl = url("http://localhost:5173/verify-email/{$verificationToken}");
         Mail::to($user->email)->send(new VerificationMail($verificationUrl));
 
         $success['name'] =  $user->name;
         $success['verification_required'] = true;
         return $this->sendResponse($success, 'User register successfully. Please verify your email.');
     }

     // Xác nhận email api
     public function verifyEmail($token): JsonResponse
     {
        $user = User::where('verification_token', $token)->first();

        if(!$user) {
            return $this->sendError('Token không hợp lệ.', [], 400);
        }

        if($user->verification_expires_at < now()) {
            return $this->sendError('Token đã hết hạn.', [], 400);
        }
 
        $user->is_verified = true;
        $user->verification_token = NULL;
        $user->verification_expires_at = NULL;
        $user->save();

        return $this->sendResponse(['verified' => true], 'Email Verify Successfully.');
     }
 
     // Login api
     public function login(Request $request): JsonResponse
     {
         if(Auth::attempt(['email' => $request->email, 'password' => $request->password,])){
             $user = Auth::user();
    
             if(!$user->is_verified){
                return $this->sendError('Tài khoản của bạn chưa được xác minh. Vui lòng kiểm tra lại email trong hộp thư.');
             }

             $success['token'] =  $user->createToken('MyApp')->plainTextToken;
             $success['name'] =  $user->name;
 
             return $this->sendResponse($success, 'Đăng nhập thành công..');
         }
         else{
             return $this->sendError('Không có quyền truy cập.', ['error'=>'Không có quyền truy cập']);
         }
     }

    //Lấy lại mật khẩu api
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        DB::table('password_reset_tokens')
        ->where('created_at', '<', now()->subMinutes(30))
        ->delete();

        $token = Str::random(64);

        //lưu token vào db
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        //gửi email với link đặt mk
        $resetLink = url("http://localhost:5173/reset-password/{$token}?email={$request->email}");
        Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

        return response()->json(['message' => 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.',
                                 'email_sent' => true ]);
    }

    // reset mật khẩu api
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required',
        ]);

        // Kiểm tra token trong bảng password_reset_tokens
        $reset = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();
        
        if(!$reset || $reset->created_at < now()->subMinutes(30)) {
            return response()->json(['message' => 'Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
                                     'password_reset' => false
                                    ], 400);
        }

        //đặt lại mật khẩu mới

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        //xóa token sau khi sử dung
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message'=> 'Password has been reset successfully.',
                                 'password_reset' => true]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'User logged out successfully.');
    }
}
