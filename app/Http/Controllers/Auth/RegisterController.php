<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('admin.auth.register'); // Ensure you have this view file
    }

    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Flasher\Prime\FlasherInterface $flasher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request, FlasherInterface $flasher)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'This email is already taken.',
            'password.confirmed' => 'Password confirmation does not match.',
            'UserName' => 'required|string|max:50|unique:users,UserName', // Add validation for UserName
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'UserName' => $request->username,
        ]);

        // Log the user in
        Auth::login($user);

        // Flash success message
        $flasher->addSuccess('Registration successful!');

        // Redirect to the admin dashboard
        return redirect()->intended('/admin');
    }

    /**
     * Log the user out.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Flasher\Prime\FlasherInterface $flasher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request, FlasherInterface $flasher)
    {
        // Log out the user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Flash success message
        $flasher->addSuccess('Successfully logged out!');

        // Redirect to the login page
        return redirect()->route('login');
    }
}
