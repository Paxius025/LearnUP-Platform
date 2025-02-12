<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class DashboardManagementController extends Controller
{
    public function index()
    {
        // ดึงโพสต์ที่ยังไม่อนุมัติ (pending)
        $pendingCount = Post::where('status', 'pending')->latest()->paginate(6);
        $totalPosts = Post::count();
        $approvedPosts = Post::where('status', 'approved')->count();
        $pendingPosts = Post::where('status', 'pending')->count();
        $rejectedPosts = Post::where('status', 'rejected')->count();
        return view('admin.dashboard', compact('pendingPosts', 'totalPosts', 'approvedPosts', 'pendingCount', 'rejectedPosts'));
    }
}