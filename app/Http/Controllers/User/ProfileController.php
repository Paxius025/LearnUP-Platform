<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\FavoritePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * แสดงหน้าแก้ไขโปรไฟล์
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * อัปเดตข้อมูลโปรไฟล์
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'], // รองรับไฟล์ภาพสูงสุด 2MB
        ]);

        // อัปเดตข้อมูลทั่วไป
        $user->name = $request->input('name');
        $user->bio = $request->input('bio');

        // จัดการอัปโหลดไฟล์ avatar
        if ($request->hasFile('avatar')) {
            // ลบรูปเก่า (ถ้ามี)
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // อัปโหลดรูปใหม่
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('avatars', $filename, 'public'); // จัดเก็บใน storage/app/public/avatars

            // บันทึกชื่อไฟล์ลงฐานข้อมูล
            $user->avatar = $filename;
        }

        $user->save();
        logAction('update_profile', "User {$user->username} updated profile");

        return redirect()->route('profile.edit')->with('success', 'โปรไฟล์ของคุณได้รับการอัปเดตแล้ว');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $allPosts = Post::where('user_id', $id)->pluck('id');

        $posts = Post::where('user_id', $id)->latest()->paginate(4);

        $totalLikes = Like::whereIn('post_id', $allPosts)->count();

        $totalBookmarks = FavoritePost::whereIn('post_id', $allPosts)->count();
        
        logAction('view_profile', "Viewed profile of user: {$user->username}");

        return view('user.profile.show', compact('user', 'posts', 'totalLikes', 'totalBookmarks'));
    }

    
}