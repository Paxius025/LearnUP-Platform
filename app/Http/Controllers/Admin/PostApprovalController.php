<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

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

        return redirect()->route('admin.dashboard')->with('success', 'Post approved successfully.');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);

        return redirect()->route('admin.dashboard')->with('success', 'Post rejected successfully.');
    }
}