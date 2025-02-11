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

        // 🔹 ดึง path ของรูปภาพจาก content
        preg_match_all('/<img.*?src=["\'](.*?storage\/posts\/.*?)["\'].*?>/i', $request->content, $matches);

$imagePaths = array_map(function ($path) {
return ltrim(str_replace(asset('storage/'), '', $path), '/');
}, $matches[1] ?? []);

// 🔹 แปลงให้เป็น JSON ก่อนบันทึก
$imagePathsJson = !empty($imagePaths) ? json_encode($imagePaths) : null;

$post = Post::create([
'user_id' => Auth::id(),
'title' => $request->title,
'content' => $request->content, // เก็บ HTML เต็มรูปแบบ
'image' => $imagePathsJson, // ✅ บันทึกเป็น JSON array
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
logAction('notify_admin', "Notified admins about new post: {$post->title}");
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

$newStatus = ($post->status === 'approved') ? 'pending' : $post->status;

// 🔹 ดึง path ของรูปภาพจาก content ใหม่
preg_match_all('/<img.*?src=["\'](.*?storage\ /posts\/.*?)["\'].*?>/i', $request->content, $matches);
    $imagePaths = array_map(function ($path) {
    return ltrim(str_replace(asset('storage/'), '', $path), '/');
    }, $matches[1] ?? []);

    // 🔹 แปลงให้เป็น String ไม่ใช่ JSON array
    $imagePath = count($imagePaths) > 0 ? $imagePaths[0] : null;

    $status = $newStatus;

    $post = Post::create([
    'user_id' => Auth::id(),
    'title' => $request->title,
    'content' => $request->content, // เก็บ HTML เต็มรูปแบบ
    'image' => $imagePath, // ✅ บันทึกเฉพาะ path เดียว ไม่ใช่ JSON array
    'pdf_file' => $pdfPath,
    'status' => $status,
    ]);

    logAction('update_post', "Updated post: {$post->title}");

    if ($newStatus === 'pending') {
    $admins = \App\Models\User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
    Notification::create([
    'user_id' => $admin->id,
    'type' => 'updated_post',
    'message' => "โพสต์ \"{$post->title}\" ถูกแก้ไขและรอการอนุมัติใหม่",
    'is_read' => false,
    ]);
    }
    logAction('notify_admin', "แจ้งเตือน Admin ว่ามีโพสต์ถูกแก้ไข: {$post->title}");
    }

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
    logAction('upload_Image', "Uploaded image: {$filename}");
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
    if ($post->status !== 'approved' && $post->user_id !== Auth::user()->id) {
    abort(403, 'You are not authorized to view this post.');
    }

    logAction('create_post', "Created post: {$post->title}");



    return view('user.posts.detail', compact('post'));
    }

    public function search(Request $request)
    {
    $query = $request->input('query');
    $posts = Post::where('title', 'like', "%$query%")
    ->orWhere('content', 'like', "%$query%")
    ->get();

    return view('user.dashboard', compact('posts'));
    }

    }