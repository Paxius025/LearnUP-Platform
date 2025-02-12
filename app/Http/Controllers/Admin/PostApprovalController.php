<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class PostApprovalController extends Controller
{
    public function detail(Post $post)
    {
        // ตรวจสอบว่าโพสต์อยู่ในสถานะรออนุมัติ
        if ($post->status !== 'pending') {
            return redirect()->route('admin.dashboard')->with('error', 'This post is already processed.');
        }

        return view('admin.approval', compact('post'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);
    
        // ✅ แจ้งเตือน User ว่าโพสต์ของเขาถูกอนุมัติ
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => "Your post has been approved \"{$post->title}\"",
            'is_user_read' => false,  // เปลี่ยนเป็น 'is_user_read'
            'is_admin_read' => false, // หรือ 'is_admin_read' หากต้องการให้ Admin อ่าน
        ]);
    
        // ✅ เก็บ Log การอนุมัติ
        logAction('approve_post', "Admin approve post: {$post->title}");
    
        return redirect()->route('admin.manage.posts')->with('success', 'Post approved successfully.');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);

        // ✅ แจ้งเตือน User ว่าโพสต์ของเขาถูกปฏิเสธ
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => "Post \"{$post->title}\" rejeted",
            'is_user_read' => false,  // เปลี่ยนเป็น 'is_user_read'
            'is_admin_read' => false, // หรือ 'is_admin_read' หากต้องการให้ Admin อ่าน
        ]);

        // ✅ เก็บ Log การปฏิเสธโพสต์
        logAction('reject_post', "Admin reject: {$post->title}");

        return redirect()->route('admin.dashboard')->with('success', 'Post rejected successfully.');
    }
}