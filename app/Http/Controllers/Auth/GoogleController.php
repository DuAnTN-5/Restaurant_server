<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Flasher\Prime\FlasherInterface;

class GoogleController extends Controller
{
    // Lấy URL đăng nhập Google
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->stateless()->redirect();
        } catch (\Exception $exception) {
            return redirect()->route('login')->with('error', 'Không thể lấy URL đăng nhập Google.');
        }
    }

    // Xử lý callback từ Google
    public function handleGoogleCallback(FlasherInterface $flasher)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
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
                'password' => bcrypt('123456dummy'), // Mật khẩu ngẫu nhiên
            ]);

            Auth::login($user);
            $flasher->addSuccess('Đăng nhập Google thành công!');
            return redirect()->intended('/admin');
        } catch (\Exception $exception) {
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }
    }
}
