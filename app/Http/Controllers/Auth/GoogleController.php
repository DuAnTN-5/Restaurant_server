<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    // Lấy URL đăng nhập Google
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->stateless()->redirect();
        } catch (\Exception $exception) {
            Log::error('Error during Google login redirect: ' . $exception->getMessage());
            return redirect()->route('login')->with('error', 'Không thể lấy URL đăng nhập Google.');
        }
    }

    // Xử lý callback từ Google
    public function handleGoogleCallback(FlasherInterface $flasher)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser->email) {
                return redirect()->route('login')->with('error', 'Không thể lấy email từ Google.');
            }

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                Auth::login($user);
                $flasher->addSuccess('Đăng nhập Google thành công!');
                return redirect()->intended('/admin');
            }

            // Tạo người dùng mới nếu chưa tồn tại
            $user = User::create([
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'password' => bcrypt(uniqid()), // Tạo mật khẩu ngẫu nhiên
            ]);

            Auth::login($user);
            $flasher->addSuccess('Đăng nhập Google thành công!');
            return redirect()->intended('/admin');
        } catch (\Exception $exception) {
            Log::error('Error during Google login callback: ' . $exception->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }
    }
}
