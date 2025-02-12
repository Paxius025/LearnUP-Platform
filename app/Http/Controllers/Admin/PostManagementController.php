<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostManagementController extends Controller
{
    public function index()
    {
        // ดึงโพสต์ที่ยังไม่อนุมัติ (pending)
        $pendingPosts = Post::where('status', 'pending')->latest()->get(); 
        $totalPosts = Post::count();
        $approvedPosts = Post::where('status', 'approved')->count();
        $pendingCount = Post::where('status', 'pending')->count();
        $rejectedPosts = Post::where('status', 'rejected')->count();

        return view('admin.manage.post', compact('pendingPosts', 'totalPosts', 'approvedPosts', 'pendingCount', 'rejectedPosts'));
    }
}