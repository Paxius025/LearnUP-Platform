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
            'message' => "โพสต์ \"{$post->title}\" ของคุณได้รับการอนุมัติ",
            'is_read' => false,
        ]);
    
        // ✅ เก็บ Log การอนุมัติ
        logAction('approve_post', "Admin อนุมัติโพสต์: {$post->title}");
    
        return redirect()->route('admin.dashboard')->with('success', 'Post approved successfully.');
    }
    

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);

        // ✅ แจ้งเตือน User ว่าโพสต์ของเขาถูกปฏิเสธ
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => "โพสต์ \"{$post->title}\" ของคุณถูกปฏิเสธ",
            'is_read' => false,
        ]);

        // ✅ เก็บ Log การปฏิเสธโพสต์
        logAction('reject_post', "Admin ปฏิเสธโพสต์: {$post->title}");

        return redirect()->route('admin.dashboard')->with('success', 'Post rejected successfully.');
    }

}