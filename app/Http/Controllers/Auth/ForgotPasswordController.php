<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Flasher\Prime\FlasherInterface;

class ForgotPasswordController extends Controller
{
    /**
     * Hiển thị form yêu cầu đặt lại mật khẩu.
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgotPassword');
    }

    /**
     * Gửi email liên kết đặt lại mật khẩu.
     */
    public function sendResetLinkEmail(Request $request, FlasherInterface $flasher)
    {
        // Validate email nhập vào
        $request->validate(['email' => 'required|email']);

        // Thử gửi liên kết đặt lại mật khẩu
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Nếu liên kết gửi thành công
        if ($status === Password::RESET_LINK_SENT) {
            $flasher->addSuccess('Liên kết đặt lại mật khẩu đã được gửi!');
            return redirect()->back(); // Trả về trang trước với thông báo thành công
        }

        // Nếu có lỗi, hiển thị thông báo lỗi
        $flasher->addError('Không thể gửi liên kết đặt lại mật khẩu.');
        return redirect()->back(); // Trả về trang trước với thông báo lỗi
    }
}
