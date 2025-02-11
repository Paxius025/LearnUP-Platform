<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoritePost;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends Controller
{
    // บันทึกหรือยกเลิก Bookmark
    public function toggle($postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        $favorite = FavoritePost::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($favorite) {
            // ถ้ามีอยู่แล้วให้ลบออก (ยกเลิก Bookmark)
            $favorite->delete();
            return response()->json(['bookmarked' => false]);
        } else {
            // ถ้ายังไม่มีให้เพิ่ม (Bookmark)
            FavoritePost::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            return response()->json(['bookmarked' => true]);
        }
    }
}