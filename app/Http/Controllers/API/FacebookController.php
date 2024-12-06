<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FacebookController extends Controller
{
    /**
     * Lấy URL đăng nhập Facebook.
     *
     * @return JsonResponse
     */
    public function redirectToFacebook(): JsonResponse
    {
        try {
            $redirectUrl = Socialite::driver('facebook')->redirect()->getTargetUrl();
            return response()->json(['url' => $redirectUrl]);
        } catch (\Exception $exception) {
            Log::error('Error during Facebook login redirect: ' . $exception->getMessage());
            return response()->json(['error' => 'Không thể lấy URL đăng nhập Facebook.'], 500);
        }
    }

    /**
     * Xử lý callback từ Facebook.
     *
     * @return JsonResponse
     */
    public function handleFacebookCallback(): JsonResponse
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            if (!$facebookUser->email) {
                return response()->json(['error' => 'Không thể lấy email từ Facebook.'], 400);
            }

            $user = User::where('email', $facebookUser->email)->first();

            if ($user) {
                Auth::login($user);
            } else {
                // Tạo người dùng mới nếu chưa tồn tại
                $user = User::create([
                    'email' => $facebookUser->email,
                    'name' => $facebookUser->name,
                    'facebook_id' => $facebookUser->id,
                    'password' => Hash::make(uniqid()), // Mật khẩu ngẫu nhiên
                ]);
                Auth::login($user);
            }

            $token = $user->createToken('SocialLoginToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $exception) {
            Log::error('Error during Facebook login callback: ' . $exception->getMessage());
            return response()->json(['error' => 'Đăng nhập Facebook thất bại. Vui lòng thử lại.'], 500);
        }
    }
}
