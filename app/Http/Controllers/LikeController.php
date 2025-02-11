<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($postId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'กรุณาล็อกอินก่อนทำการไลค์'], 401);
        }

        $userId = Auth::id();  
        $liked = Like::toggleLike($userId, $postId);

        // ส่งกลับผลการดำเนินการ
        return response()->json([
            'liked' => $liked,
            'message' => $liked ? 'Like' : 'Unlike',
        ]);

        logAction('toggle_like', "User " . Auth::user()->username . " liked post: $postId");
    }
}