<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flasher\Prime\FlasherInterface;
use App\Models\User;
class LoginController extends Controller
{
    public function showLoginForm(){
        return view('admin.auth.login');
    }
    
    public function login(Request $request, FlasherInterface $flasher)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:6',
    ], [
        'email.exists' => 'Email này không tồn tại trong hệ thống.',
    ]);

    // Kiểm tra trạng thái tài khoản
    $user = User::where('email', $request->email)->first();
    if ($user && $user->status == 'inactive') {
        $flasher->addError('Tài khoản của bạn đã bị vô hiệu hóa.');
        return back();
    }

    if (Auth::attempt($request->only('email', 'password'))) {
        $flasher->addSuccess('Đăng nhập thành công!');
        return redirect()->route('admin.index');
    }

    $flasher->addError('Thông tin đăng nhập không chính xác.');
    return back();
}

    public function logout(Request $request, FlasherInterface $flasher)
    {
        Auth::logout(); 

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        $flasher->addSuccess('Đăng xuất thành công!');
        return redirect('/login'); 
    }
}
