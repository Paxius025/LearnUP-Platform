<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
class DashboardController extends Controller
{
    public function index()
    {   
        $posts = Post::where('status', 'approved')->latest()->get();
        return view('user.dashboard',compact('posts'));
    }
}