<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get(); // ไม่แสดง Admin
        return view('admin.users', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,writer,admin',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if (Auth::id() === $user->id) {
            Auth::logout();
            $user = User::find($user->id); 
            Auth::login($user);
            session()->regenerate();
        }
        logAction('update_user', "Updated user: {$user->name}");
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        logAction('delete_user', "Deleted user: {$user->name}");
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}