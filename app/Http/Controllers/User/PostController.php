<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Log;
use App\Models\Notification;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        return view('user.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('user.posts.create');
    }

    public function edit(Post $post)
    {
        // ตรวจสอบว่าผู้ใช้เป็นเจ้าของโพสต์หรือไม่
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.posts.edit', compact('post'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:5120', // รองรับไฟล์ PDF ไม่เกิน 5MB
        ]);

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
        }

        $status = in_array(Auth::user()->role, ['admin', 'writer']) ? 'approved' : 'pending';

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'pdf_file' => $pdfPath,
            'status' => $status,
        ]);

        logAction('create_post', "Created post: {$post->title}");
        
        if ($status === 'pending') {
            $admins = \App\Models\User::where('role', 'admin')->get(); // ดึง Admin ทุกคน
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_post',
                    'message' => "โพสต์ใหม่ \"{$post->title}\" รออนุมัติ",
                    'is_read' => false,
                ]);
            }
            logAction('notify_admin', "แจ้งเตือน Admin ว่ามีโพสต์ใหม่: {$post->title}");
        }

        return redirect()->route('user.posts.index')->with('success', 'Post created successfully.');
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:5120',
        ]);

        $pdfPath = $post->pdf_file;
        if ($request->hasFile('pdf_file')) {
            if ($pdfPath) {
                Storage::delete("public/{$pdfPath}");
            }
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'pdf_file' => $pdfPath,
        ]);

        return redirect()->route('user.posts.index')->with('success', 'Post updated successfully.');
    }
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        } 

        logAction('delete_post', "Deleted post: {$post->title}");
        
        if ($post->pdf_file) {
            Storage::delete("public/{$post->pdf_file}");
        }

        $post->delete();

        return redirect()->route('user.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('posts', $filename, 'public');

        return response()->json(['url' => asset("storage/{$path}")]);
    }


    public function show(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.posts.show', compact('post'));
    }

    public function detail(Post $post)
    {
        // ตรวจสอบว่าโพสต์ถูกอนุมัติหรือเป็นเจ้าของโพสต์
        if ($post->status !== 'approved' && $post->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to view this post.');
        }
        
        logAction('create_post', "Created post: {$post->title}");

        
        
        return view('user.posts.detail', compact('post'));
    }

}