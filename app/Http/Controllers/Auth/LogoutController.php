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
        // Retrieve the User before logging out
        $user = Auth::user();
    
        // Save log before logging out
        logAction('logout', "User {$user->name} ({$user->email}) logged out.");
    
        // Log out
        Auth::logout();
    
        // Clear session to prevent session hijacking
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('home');
    }
}