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
use Illuminate\Support\Facades\Hash;


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
          $input["verification_expires_at"] = now()->addMinute(5);

         $user = User::create($input);
        //  $success['token'] =  $user->createToken('MyApp')->plainTextToken;

         $verificationUrl = url("https://hightfiverestaurant.store/verify-email/{$verificationToken}");
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
            $user->delete();  
            return $this->sendError('Token đã hết hạn và tài khoản đã bị xóa.', [], 400);
        }
 
        $user->is_verified = true;
        $user->verification_token = NULL;
        $user->verification_expires_at = NULL;
        $user->save();

        $user->assignRole('User');
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

             if($user->status == "inactive"){
                return $this->sendError('Tài khoản của bạn đã bị khóa.');
             }

             $success['token'] =  $user->createToken('MyApp')->plainTextToken;
             $success['user'] =  $user;
 
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
        $resetLink = url("https://hightfiverestaurant.store/reset-password/{$token}?email={$request->email}");
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
        
        if(!$reset || $reset->created_at < now()->subMinutes(5)) {
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

    // API Đổi Mật Khẩu
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if($validator->fails()){
            return $this->sendError('Lỗi xác thực.', $validator->errors());
        }

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!\Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Mật khẩu hiện tại không đúng.', [], 400);
        }

        // Cập nhật mật khẩu mới
        $user->password = bcrypt($request->new_password);
        $user->save();

        return $this->sendResponse([], 'Mật khẩu đã được cập nhật thành công.');
    }

    public function updateUserInfo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . Auth::id(),
            'phone_number' => 'sometimes|required|numeric',
            'address' => 'sometimes|required|string',
            'image' => 'sometimes|nullable|string',
        ]);
    
        if($validator->fails()){
            return $this->sendError('Lỗi xác thực.', $validator->errors());
        }
    
        $user = Auth::user();
    
        // Cập nhật thông tin người dùng
        if ($request->has('name')) {
            $user->name = $request->name;
        }
    
        if ($request->has('email')) {
            $user->email = $request->email;
        }
    
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
    
        if ($request->has('address')) {
            $user->address = $request->address;
        }
    
        if ($request->has('image')) {
            // Lấy chuỗi base64 từ frontend
            $imageData = $request->image;
    
            // Loại bỏ tiền tố base64 (data:image/jpeg;base64,...)
            $image = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $imageData);
    
            // Giải mã base64 thành dữ liệu nhị phân
            $image = base64_decode($image);
    
            // Tạo tên file ngẫu nhiên cho ảnh
            $imageName = Str::random(10) . '.jpg';  // Nếu bạn muốn lưu ảnh ở dạng .jpg, có thể thay đổi thành .png nếu cần
    
            // Lưu ảnh vào thư mục public/userfiles/image/
            $path = public_path('userfiles/image/' . $imageName);
            file_put_contents($path, $image);
    
            // Cập nhật đường dẫn vào cơ sở dữ liệu
            $user->image = 'userfiles/image/' . $imageName;
        }
    
        // Lưu thông tin người dùng vào cơ sở dữ liệu
        $user->save();
    
        return $this->sendResponse($user, 'Thông tin người dùng đã được cập nhật thành công.');
    }
    


}
