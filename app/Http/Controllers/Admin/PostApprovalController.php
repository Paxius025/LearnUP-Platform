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
        $authAdmin = Auth::user(); // Get the admin who approved the post

        $post->update(['status' => 'approved']);

        // Notify the post owner that their post has been approved
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => "Your post \"{$post->title}\" has been approved.",
            'is_read' => false,
        ]);

        // Notify the admin who approved the post
        Notification::create([
            'user_id' => $authAdmin->id, 
            'type' => 'admin_post_approved',
            'message' => "You approved the post: \"{$post->title}\".",
            'is_read' => false           
        ]);

        // Log the approval action
        logAction('approve_post', "Admin {$authAdmin->name} approved post: {$post->title}");
        logAction('notify_admin', "Admin {$authAdmin->name} received notification: " . json_encode($notification));
        dd($notification);
        return redirect()->route('admin.dashboard')->with('success', 'Post approved successfully.');
    }

    public function reject(Post $post)
    {
        $authAdmin = Auth::user(); // Get the admin who rejected the post

        $post->update(['status' => 'rejected']);

        // Notify the post owner that their post has been rejected
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => "Your post \"{$post->title}\" has been rejected.",
            'is_read' => false,
        ]);

        // Notify the admin who rejected the post
        Notification::create([
            'user_id' => $authAdmin->id, // Notify the admin who rejected
            'type' => 'admin_post_rejected',
            'message' => "You rejected the post: \"{$post->title}\".",
            'is_ead' => false,
        ]);

        // Log the rejection action
        logAction('reject_post', "Admin {$authAdmin->name} rejected post: {$post->title}");

        return redirect()->route('admin.dashboard')->with('success', 'Post rejected successfully.');
    }

}