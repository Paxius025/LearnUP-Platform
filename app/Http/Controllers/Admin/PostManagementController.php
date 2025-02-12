<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostManagementController extends Controller
{
    public function index()
    {
        // ตรวจสอบว่ามีโพสต์อยู่จริง
        $pendingPosts = Post::where('status', 'pending')->latest()->get() ?? collect([]);
        $approvedPosts = Post::where('status', 'approved')->latest()->paginate(10, ['*'], 'approved_page');
        $rejectedPosts = Post::where('status', 'rejected')->latest()->paginate(10, ['*'], 'rejected_page');

        // คำนวณจำนวนโพสต์แต่ละประเภท
        $totalPosts = Post::count();
        $approvedCount = Post::where('status', 'approved')->count();
        $pendingCount = Post::where('status', 'pending')->count();
        $rejectedCount = Post::where('status', 'rejected')->count();

        return view('admin.manage.post', compact(
            'pendingPosts', 'approvedPosts', 'rejectedPosts', 
            'totalPosts', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.manage.detail', compact('post'));
    }

}