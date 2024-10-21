<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FacebookController extends Controller
{
    /**
     * Chuyển hướng đến trang đăng nhập Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToFacebook(): RedirectResponse
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Xử lý callback từ Facebook và chuyển hướng đến trang mong muốn.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleFacebookCallback(): RedirectResponse
    {
        try {
            $user = Socialite::driver('facebook')->user();

            // Kiểm tra xem người dùng đã tồn tại trong cơ sở dữ liệu chưa
            $finduser = User::where('facebook_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
            } else {
                // Tạo người dùng mới với thông tin từ Facebook
                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'facebook_id' => $user->id,
                        'password' => Hash::make('123456dummy') // Sử dụng Hash::make() cho mật khẩu
                    ]
                );

                Auth::login($newUser);
            }

            // Chuyển hướng đến trang mong muốn sau khi đăng nhập
            return redirect()->route('admin'); // Thay 'desired.route' bằng route bạn muốn chuyển hướng đến
        } catch (Exception $e) {
            // Xử lý ngoại lệ và hiển thị thông báo lỗi
            return redirect()->route('login')->with('error', 'Failed to login with Facebook: ' . $e->getMessage());
        }
    }
}
