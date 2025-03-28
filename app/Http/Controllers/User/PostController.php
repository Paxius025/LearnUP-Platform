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
    public function index(Request $request)
    {
        $query = Post::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        $posts = $query->paginate(9);

        if ($request->ajax()) {
            return view('user.posts.partials.post_table', compact('posts'))->render();
        }

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
        // Validate inputs
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:51200', // Support PDF files up to 50MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480', // Support image files up to 20MB
        ]);

        // Handle PDF upload
        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store the image file and get the file path
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Determine post status based on user role
        $status = Auth::user()->role === 'user' ? 'pending' : 'approved';
        $authUser = Auth::user(); // Get the currently logged-in user

        // Create the post in the database
        $post = Post::create([
            'user_id' => $authUser->id, // Use the current logged-in user ID
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath, // Store image path if available
            'pdf_file' => $pdfPath, // Store PDF path if available
            'status' => $status,
        ]);

        // Log the action of creating a post
        logAction('create_post', "Created post: {$post->title}");

        // Notify the user about the successful post creation
        Notification::create([
            'user_id' => $authUser->id, // Notify the user themselves
            'type' => 'New_post',
            'message' => "You have successfully created a post: \"{$post->title}\"",
            'is_read' => false,
        ]);

        // If the post is pending, notify the admins
        if ($status === 'pending') {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id, // Notify Admins
                    'type' => 'new_post',
                    'message' => "New post \"{$post->title}\" by {$authUser->name} is pending approval.",
                    'is_read' => false,
                ]);
            }
            logAction('notify_admin', "Notified admins about new post: {$post->title}");
        }

        // Redirect back with success message
        return redirect()->route('user.posts.index')->with('success', 'Post created successfully.');
    }


    public function update(Request $request, Post $post)
    {
        $authUser = Auth::user(); // ✅ Retrieve the currently logged-in user

        // Check if the post belongs to the logged-in user
        if ($post->user_id !== $authUser->id) {
            abort(403); // If not the owner, stop the process
        }

        // Validate the data received from the form
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:50000', // Max 50MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480', // Max 20MB
        ]);

        // Handle PDF file
        $pdfPath = $post->pdf_file; // Store the old PDF file path
        if ($request->hasFile('pdf_file')) {
            if ($pdfPath) {
                Storage::delete("public/{$pdfPath}");
            }
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
        }

        // Handle image (cover image)
        $imagePath = $post->image; // Keep the old image path
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($imagePath) {
                Storage::delete("public/{$imagePath}");
            }
            // Upload the new image
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Set status to 'pending' if role is 'user' and 'approved' for other roles
        $newStatus = $authUser->role === 'user' ? 'pending' : 'approved';

        // Update the post
        $post->update([
            'title' => $request->title,
            'content' => $request->content, // Store full HTML content
            'image' => $imagePath, // Update image path (new or old)
            'pdf_file' => $pdfPath,
            'status' => $newStatus, // Update status based on role
        ]);

        logAction('update_post', "Updated post: {$post->title}");

        // ✅ Notify the user that the post was successfully updated
        Notification::create([
            'user_id' => $authUser->id,
            'type' => 'self_update_post',
            'message' => "You have successfully updated your post: \"{$post->title}\"",
            'is_read' => false,
        ]);

        // ✅ Notify **Admin** that a post has been edited and is pending approval
        if ($newStatus === 'pending') {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id, // ✅ Notify Admin using `admin->id`
                    'type' => 'updated_post',
                    'message' => "Post \"{$post->title}\" was edited by {$authUser->name} and is pending approval",
                    'is_read' => false,
                ]);
            }
            logAction('notify_admin', "Notified Admin about edited post: {$post->title}");
        }

        return redirect()->route('user.posts.index')->with('success', 'Post updated successfully.');
    }



    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480', // Max 20MB
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

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->user()->id !== $post->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this post.');
        }
        $post->delete();

        return redirect()->route('user.posts.index')->with('success', 'Post deleted successfully.');
    }
}