<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class PostManagementController extends Controller
{
    public function index()
    {
        // Check if there are any posts
        $pendingPosts = Post::where('status', 'pending')->latest()->get() ?? collect([]);
        $approvedPosts = Post::where('status', 'approved')->latest()->paginate(9, ['*'], 'approved_page');
        $rejectedPosts = Post::where('status', 'rejected')->latest()->paginate(9, ['*'], 'rejected_page');

        // Calculate the number of posts for each status
        $totalPosts = Post::count();
        $approvedCount = Post::where('status', 'approved')->count();
        $pendingCount = Post::where('status', 'pending')->count();
        $rejectedCount = Post::where('status', 'rejected')->count();

        return view('admin.manage.post', compact(
            'pendingPosts', 'approvedPosts', 'rejectedPosts', 
            'totalPosts', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }

    public function detail(Post $post)
    {
        return view('admin.manage.detail', compact('post'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.manage.detail', compact('post'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);
    
        // Notify the user that their post has been approved
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => "Your post has been approved \"{$post->title}\"",
            'is_read' => false,
        ]);
    
        // Log the approval action
        logAction('approve_post', "Admin approved post: {$post->title}");
    
        return redirect()->route('admin.manage.posts')->with('success', 'Post approved successfully.');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);

        // Notify the user that their post has been rejected
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => "Post \"{$post->title}\" rejected",
            'is_read' => false,  // Change to 'is_user_read'
        ]);

        // Log the rejection action
        logAction('reject_post', "Admin rejected post: {$post->title}");

        return redirect()->route('admin.manage.posts')->with('success', 'Post rejected successfully.');
    }
}