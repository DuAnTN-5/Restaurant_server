<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SocialAuthController extends Controller
{
    /**
     * Chuyển hướng đến trang đăng nhập của nhà cung cấp.
     *
     * @param string $provider
     * @return JsonResponse
     */
    public function redirectToProvider(string $provider): JsonResponse
    {
        try {
            $redirectUrl = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
            return response()->json(['url' => $redirectUrl]);
        } catch (\Exception $exception) {
            Log::error("Error during $provider login redirect: " . $exception->getMessage());
            return response()->json(['error' => "Không thể lấy URL đăng nhập $provider."], 500);
        }
    }

    /**
     * Xử lý callback từ nhà cung cấp và đăng nhập người dùng.
     *
     * @param string $provider
     * @return JsonResponse
     */
    public function handleProviderCallback(string $provider): JsonResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Kiểm tra email có hợp lệ không (tránh trường hợp không lấy được email từ Facebook)
            if (!$socialUser->email) {
                return response()->json(['error' => "Không thể lấy email từ $provider."], 400);
            }

            // Tìm người dùng dựa trên email
            $user = User::where('email', $socialUser->email)->first();

            if ($user) {
                // Đăng nhập nếu người dùng đã tồn tại
                Auth::login($user);
            } else {
                // Tạo người dùng mới nếu chưa tồn tại
                $user = User::create([
                    'email' => $socialUser->email,
                    'name' => $socialUser->name,
                    $provider . '_id' => $socialUser->id,
                    'password' => Hash::make(uniqid()), // Mật khẩu ngẫu nhiên
                ]);

                Auth::login($user);
            }

            // Tạo token API cho người dùng đã đăng nhập
            $token = $user->createToken('SocialLoginToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $exception) {
            Log::error("Error during $provider login callback: " . $exception->getMessage());
            return response()->json(['error' => "Đăng nhập $provider thất bại. Vui lòng thử lại."], 500);
        }
    }
}

