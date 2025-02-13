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
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->paginate(9);
        return view('user.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('user.posts.create');
    }

    public function edit(Post $post)
    {
        // Check if the user is the owner of the post
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
            'pdf_file' => 'nullable|mimes:pdf|max:5120', // Support PDF files up to 5MB
        ]);

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
        }

        $status = Auth::user()->role === 'user' ? 'pending' : 'approved';

        // Extract image paths from content
        preg_match_all('/<img.*?src=["\'](.*?storage\/posts\/.*?)["\'].*?>/i', $request->content, $matches);

$imagePaths = array_map(function ($path) {
return ltrim(str_replace(asset('storage/'), '', $path), '/');
}, $matches[1] ?? []);

// Convert to JSON before saving
$imagePathsJson = !empty($imagePaths) ? json_encode($imagePaths) : null;

$post = Post::create([
'user_id' => Auth::id(),
'title' => $request->title,
'content' => $request->content, // Store full HTML content
'image' => $imagePathsJson, // Save as JSON array
'pdf_file' => $pdfPath,
'status' => $status,
]);

logAction('create_post', "Created post: {$post->title}");

if ($status === 'pending') {
// Notify all admins about the new post
$admins = \App\Models\User::where('role', 'admin')->get();
foreach ($admins as $admin) {
Notification::create([
'user_id' => $post->user->id,
'type' => 'new_post',
'message' => "\"{$post->title}\" by " . $post->user->name,
'created_at' => now(),
'is_user_read' => false,
'is_admin_read' => false,
]);
}
logAction('notify_admin', "Notified admins about new post: {$post->title}");
}

return redirect()->route('user.posts.index')->with('success', 'Post created successfully.');
}

public function update(Request $request, Post $post)
{
// Check if the post belongs to the logged-in user
if ($post->user_id !== Auth::id()) {
abort(403); // If not the owner, stop the process
}

// Validate the data received from the form
$request->validate([
'title' => 'required|string|max:255',
'content' => 'required',
'pdf_file' => 'nullable|mimes:pdf|max:5120',
]);

$pdfPath = $post->pdf_file; // Store the old PDF file path
// If a new PDF file is uploaded
if ($request->hasFile('pdf_file')) {
// Delete the old PDF file if it exists
if ($pdfPath) {
Storage::delete("public/{$pdfPath}");
}
// Upload the new PDF file
$pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
}

// Set status to 'pending' if role is 'user' and 'approved' for other roles
$newStatus = Auth::user()->role === 'user' ? 'pending' : 'approved';

// Extract image paths from the new content
preg_match_all('#<img.*?src=["\'](.*?storage /posts/.*?)["\'].*?>#i', $request->content, $matches);
    $imagePaths = array_map(function ($path) {
    return ltrim(str_replace(asset('storage/'), '', $path), '/');
    }, $matches[1] ?? []);

    // Convert to String, not JSON array
    $imagePath = count($imagePaths) > 0 ? $imagePaths[0] : null;

    // Update the post
    $post->update([
    'title' => $request->title,
    'content' => $request->content, // Store full HTML content
    'image' => $imagePath, // Save only one path, not JSON array
    'pdf_file' => $pdfPath,
    'status' => $newStatus, // Update status based on role
    ]);

    logAction('update_post', "Updated post: {$post->title}");

    // Notify Admin when the post is edited and pending approval
    if ($newStatus === 'pending') {
    // Notify only Admins
    $admins = \App\Models\User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
    Notification::create([
    'user_id' => $post->user->id,
    'type' => 'updated_post',
    'message' => "Post \"{$post->title}\" was edited by " . $post->user->name . " and is pending approval",
    'is_user_read' => false,
    'is_admin_read' => false,
    ]);
    logAction('notify_admin', "Notified Admin about edited post: {$post->title}");
    }
    }

    return redirect()->route('user.posts.index')->with('success', 'Post updated successfully.');
    }

    public function uploadImage(Request $request)
    {
    $request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
    $path = $request->file('image')->storeAs('posts', $filename, 'public');
    logAction('upload_image', "Uploaded image: {$filename}");
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
    // Check if the post is approved or the user is the owner
    if ($post->status !== 'approved' && $post->user_id !== Auth::user()->id) {
    abort(403, 'You are not authorized to view this post.');
    }

    logAction('view_post', "Viewed post: {$post->title}");

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