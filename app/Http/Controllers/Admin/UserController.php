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
            'role' => 'required|in:user,writer,admin',
        ]);

        $oldRole = $user->role;

        $user->update([
            'role' => $request->role,  
        ]);

        // ส่งการแจ้งเตือนเมื่อมีการเปลี่ยน Role
        if ($oldRole !== $user->role) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'role_updated',
                'message' => "Your role has changed from {$oldRole} to {$user->role}",
                'is_user_read' => false,  
                'is_admin_read' => false, 
            ]);
        }

        logAction('update_user', "Updated user role: {$user->name} ({$oldRole} → {$user->role})");

        return redirect()->route('admin.users')->with('success', 'User role updated successfully.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        logAction('delete_user', "Deleted user: {$user->name}");
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}