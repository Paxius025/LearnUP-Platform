@extends('layouts.app')
@section('title', 'LearnUP')

@section('content')
    <!-- Navbar -->
    <div class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        @include('components.navbar')
    </div>

    <!-- Background Gradient -->
    <div class="h-screen flex flex-col justify-center items-center overflow-hidden">
        <div class="container mx-auto px-4 flex-1 flex justify-center items-center">
            <div class="w-full max-w-4xl bg-white shadow-lg rounded-xl p-6 border border-gray-200 h-[85vh] overflow-auto">   
                <!-- ส่วนหัวโปรไฟล์ -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Avatar -->
                        <div class="relative">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Avatar"
                                    class="w-20 h-20 rounded-full border-4 border-green-400 shadow-lg">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar"
                                    class="w-20 h-20 rounded-full border-4 border-green-400 shadow-lg">
                            @endif
                        </div>

                        <!-- Username & Bio -->
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-500 text-sm italic">{{ $user->bio ?? 'No information available' }}</p>
                        </div>
                    </div>

                    <!-- Likes & Bookmarks -->
                    <div class="flex flex-col items-end space-y-2">
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-sm font-medium">👍 Likes</span>
                            <span class="text-lg font-bold text-green-600">{{ $totalLikes }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-700">
                            <span class="text-sm font-medium">🔖 Bookmarks</span>
                            <span class="text-lg font-bold text-green-600">{{ $totalBookmarks }}</span>
                        </div>
                    </div>
                </div>

                <!-- รายการโพสต์ -->
                <div class="mt-4 space-y-4">
                    @forelse ($posts as $post)
                        <div class="p-4 bg-gray-50 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-all duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $post->title }}</h4>
                                    <p class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                                </div>

                                <!-- จำนวนไลค์, บุ๊คมาร์ค และ Read More -->
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="w-5 h-5 text-red-500" viewBox="0 0 24 24">
                                            <path
                                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                        </svg>
                                        <span class="text-sm font-medium">{{ optional($post->likes)->count() ?? 0 }}</span>
                                    </div>

                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="w-5 h-5 text-blue-500" viewBox="0 0 24 24">
                                            <path d="M6 2a2 2 0 00-2 2v18l8-5.5L20 22V4a2 2 0 00-2-2H6z" />
                                        </svg>
                                        <span class="text-sm font-medium">{{ optional($post->favorites)->count() ?? 0 }}</span>
                                    </div>

                                    <a href="{{ route('user.posts.detail', $post->id) }}"
                                        class="text-green-600 hover:text-green-800 flex items-center space-x-1 text-sm font-medium">
                                        <span>Read More</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No posts available</p>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
