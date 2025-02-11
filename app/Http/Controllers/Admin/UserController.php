<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Notification;

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

        $oldRole = $user->role;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // ส่งการแจ้งเตือนให้ผู้ใช้เมื่อมีการเปลี่ยนยศ
        if ($oldRole !== $user->role) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'role_updated',
                'message' => "Your role has changed {$oldRole} to {$user->role}",
                'is_user_read' => false,  
                'is_admin_read' => false, 
            ]);
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