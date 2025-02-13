<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\FavoritePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile edit page
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:10240'], // 10MB
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Check file size (10MB)
            if ($file->getSize() > 10 * 1024 * 1024) {
                return redirect()->route('profile.edit')->with('error', 'The uploaded image must be less than 10MB.');
            }

            // Delete old avatar (if exists)
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Upload new avatar
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('avatars', $filename, 'public');

            // Save filename to database
            $user->avatar = $filename;
        }

        $user->name = $request->input('name');
        $user->bio = $request->input('bio');
        $user->save();

        logAction('update_profile', "User {$user->username} updated profile");

        return redirect()->route('profile.edit')->with('success', 'Your profile has been updated successfully!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $allPosts = Post::where('user_id', $id)->pluck('id');

        $posts = Post::where('user_id', $id)->latest()->paginate(5);

        $totalLikes = Like::whereIn('post_id', $allPosts)->count();

        $totalBookmarks = FavoritePost::whereIn('post_id', $allPosts)->count();
        
        logAction('view_profile', "Viewed profile of user: {$user->username}");

        return view('user.profile.show', compact('user', 'posts', 'totalLikes', 'totalBookmarks'));
    }
}