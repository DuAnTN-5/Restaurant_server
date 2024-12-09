<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    /**
     * Lấy URL đăng nhập Google.
     *
     * @return JsonResponse
     */
    public function redirectToGoogle(): JsonResponse
    {
        try {
            $redirectUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
            return response()->json(['url' => $redirectUrl]);
        } catch (\Exception $exception) {
            Log::error('Error during Google login redirect: ' . $exception->getMessage());
            return response()->json(['error' => 'Không thể lấy URL đăng nhập Google.'], 500);
        }
    }

    /**
     * Xử lý callback từ Google.
     *
     * @return JsonResponse
     */
    public function handleGoogleCallback(): JsonResponse
{
    try {
        // Ghi log các query parameters từ callback URL
        Log::info('Google Callback Query Parameters:', request()->query());

        // Lấy thông tin người dùng từ Google
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (!$googleUser->email) {
            return response()->json(['error' => 'Không thể lấy email từ Google.'], 400);
        }

        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            Auth::login($user);
        } else {
            // Tạo người dùng mới nếu chưa tồn tại
            $user = User::create([
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
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
        Log::error('Error during Google login callback: ' . $exception->getMessage());
        return response()->json(['error' => 'Đăng nhập Google thất bại. Vui lòng thử lại.'], 500);
    }
}
}
