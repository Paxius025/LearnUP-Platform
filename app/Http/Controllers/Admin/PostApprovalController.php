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
        // Check if the post is in pending status
        if ($post->status !== 'pending') {
            return redirect()->route('admin.dashboard')->with('error', 'This post is already processed.');
        }

        return view('admin.approval', compact('post'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);
    
        // Notify the user that their post has been approved
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => "Your post has been approved \"{$post->title}\"",
            'is_user_read' => false,  // Change to 'is_user_read'
            'is_admin_read' => false, // Or 'is_admin_read' if you want the admin to read
        ]);
    
        // Log the approval action
        logAction('approve_post', "Admin approve post: {$post->title}");
    
        return redirect()->route('admin.dashboard')->with('success', 'Post approved successfully.');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);

        // Notify the user that their post has been rejected
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => "Post \"{$post->title}\" rejected",
            'is_user_read' => false,  // Change to 'is_user_read'
            'is_admin_read' => false, // Or 'is_admin_read' if you want the admin to read
        ]);

        // Log the rejection action
        logAction('reject_post', "Admin reject: {$post->title}");

        return redirect()->route('admin.dashboard')->with('success', 'Post rejected successfully.');
    }
}