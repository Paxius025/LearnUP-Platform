<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // ดึงข้อมูล User ก่อน Logout
        $user = Auth::user();
    
        // บันทึก Log ก่อนออกจากระบบ
        logAction('logout', "User {$user->name} ({$user->email}) logged out.");
    
        // ออกจากระบบ
        Auth::logout();
    
        // ล้าง session เพื่อป้องกัน session hijacking
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('home');
    }
    
}