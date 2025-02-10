<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * บันทึกคอมเมนต์ใหม่
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id, // ถ้าเป็น Reply จะมีค่า parent_id
            'content' => $request->content,
        ]);

        
        logAction('create_comment', "User ID " . Auth::id() . " commented on Post ID {$post->id}");

        return redirect()->back()->with('success', 'คอมเมนต์ถูกเพิ่มเรียบร้อยแล้ว');
    }

    /**
     * ลบคอมเมนต์ (เฉพาะเจ้าของคอมเมนต์หรือ Admin เท่านั้น)
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'คุณไม่มีสิทธิ์ลบคอมเมนต์นี้');
        }

        $comment->delete();

        logAction('delete_comment', "User ID " . Auth::id() . " deleted Comment ID {$comment->id}");

        return redirect()->back()->with('success', 'คอมเมนต์ถูกลบเรียบร้อยแล้ว');
    }
}