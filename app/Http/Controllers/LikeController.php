<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($postId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please login before like'], 401);
        }

        $userId = Auth::id();
        $liked = Like::toggleLike($userId, $postId);

        $likeCount = Like::where('post_id', $postId)->count();
        
        // ค้นหาเจ้าของโพสต์
        $postOwner = Post::findOrFail($postId)->user;  // Assuming the post has a 'user' relationship with User model
        
        // ถ้าเจ้าของโพสต์ไม่ใช่ผู้ที่กดไลค์โพสต์นี้
        if ($postOwner->id !== $userId) {
            // สร้างการแจ้งเตือน
            Notification::create([
                'user_id' => $postOwner->id,
                'type' => 'toggle_like',
                'message' => 'Your post has received a new like.',
                'is_read' => false,
            ]);
        }

        // ส่งกลับผลการดำเนินการ
        return response()->json([
            'liked' => $liked,
            'message' => $liked ? 'Like' : 'Unlike',
            'likeCount' => $likeCount,
        ]);

        logAction('toggle_like', "User " . Auth::user()->username . " liked post: $postId");
    }

    public function mostLikedPosts()
    {
        // คำนวณโพสต์ที่ถูกไลค์มากที่สุด (เรียงลำดับตามจำนวนไลค์)
        $mostLikedPosts = Post::withCount('likes')  // ใช้ withCount เพื่อดึงจำนวนไลค์
            ->where('status', 'approved')    
            ->orderBy('likes_count', 'desc')  // เรียงจากมากไปหาน้อย
            ->take(10)  // ดึงมาแค่ 10 โพสต์ที่มีจำนวนไลค์มากที่สุด
            ->get();

        return view('user.most_liked_posts', compact('mostLikedPosts'));
    }
}