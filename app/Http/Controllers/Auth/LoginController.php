<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ตรวจสอบข้อมูล
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // พยายามเข้าสู่ระบบ
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // ตรวจสอบ Remember Me

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Invalid email or password');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}