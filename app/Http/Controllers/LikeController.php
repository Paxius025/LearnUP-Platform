<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
class LikeController extends Controller
{
    public function toggleLike($postId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please before like'], 401);
        }

        $userId = Auth::id();  
        $liked = Like::toggleLike($userId, $postId);

        $likeCount = Like::where('post_id', $postId)->count();


        // ส่งกลับผลการดำเนินการ
        return response()->json([
            'liked' => $liked,
            'message' => $liked ? 'Like' : 'Unlike',
            'likeCount' => $likeCount,
        ]);

        logAction('toggle_like', "User " . Auth::user()->username . " liked post: $postId");
    }
}