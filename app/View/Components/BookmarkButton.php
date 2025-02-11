<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\FavoritePost;
use Illuminate\Support\Facades\Auth;

class BookmarkButton extends Component
{
    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function render()
    {
        $isBookmarked = FavoritePost::where('user_id', Auth::id())
            ->where('post_id', $this->post->id)
            ->exists();

        return view('components.bookmark-button', [
            'isBookmarked' => $isBookmarked
        ]);
    }
}