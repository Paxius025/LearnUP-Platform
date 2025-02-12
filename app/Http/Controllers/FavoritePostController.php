<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoritePost;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends Controller
{
    // บันทึกหรือยกเลิก Bookmark
    public function toggle($postId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please login before bookmarking'], 401);
        }

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

            // ค้นหาเจ้าของโพสต์
            $postOwner = $post->user; // Assuming the Post model has a 'user' relationship

            // ถ้าเจ้าของโพสต์ไม่ใช่ผู้ที่กด Bookmark
            if ($postOwner->id !== $user->id) {
                Notification::create([
                    'user_id' => $postOwner->id,
                    'type' => 'toggle_bookmark',
                    'message' => 'Someone bookmarked your post.',
                    'is_user_read' => false,
                    'is_admin_read' => false
                ]);
            }
            
            logAction('toggle_bookmark', "User " . $user->username . " bookmarked post: $postId");

            return response()->json(['bookmarked' => true]);
        }
    }

    public function bookmarkedPosts()
    {
        $user = Auth::user();

        // ดึงโพสต์ที่ถูกบันทึกไว้
        $bookmarkedPosts = Post::whereIn('id', function ($query) use ($user) {
            $query->select('post_id')
                ->from('favorite_posts')
                ->where('user_id', $user->id);
        })->latest()->paginate(10);

        logAction('view_bookmarks', "User {$user->username} viewed their bookmarks");
        return view('user.bookmarks', compact('bookmarkedPosts'));
    }
}