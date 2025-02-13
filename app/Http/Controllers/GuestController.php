<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class GuestController extends Controller
{
    public function index()
    {   
        $posts = Post::where('status', 'approved')->latest()->get();
        return view('guest', compact('posts'));
    }
}