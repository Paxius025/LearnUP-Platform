@extends('layouts.app')

@extends('layouts.app')

@section('content')
    <!-- Navbar -->
    <div class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        @include('components.navbar')
    </div>

    <div class="max-w-4xl mx-auto pt-24 overflow-hidden min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- ส่วนหัวโปรไฟล์ -->
            <div class="flex flex-col items-center space-y-4">
                @if ($user->avatar)
                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Avatar"
                        class="w-24 h-24 rounded-full border-4 border-green-500 shadow">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar"
                        class="w-24 h-24 rounded-full border-4 border-gray-300 shadow">
                @endif

                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 text-sm">{{ $user->bio ?? 'No information available' }}</p>
                </div>
            </div>

            <!-- สถิติของโพสต์ -->
            <div class="mt-6 flex justify-center space-x-6 text-gray-700">
                <div class="text-center">
                    <p class="text-lg font-semibold">{{ $totalLikes }}</p>
                    <p class="text-sm text-gray-500">Total Likes</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-semibold">{{ $totalBookmarks }}</p>
                    <p class="text-sm text-gray-500">Total Bookmarks</p>
                </div>
            </div>

            <hr class="my-6 border-gray-300">

            <!-- โพสต์ของผู้ใช้ -->
            <h3 class="text-lg font-semibold text-gray-900 text-center">Posts by {{ $user->name }}</h3>

            <div class="mt-4 space-y-4">
                @forelse ($posts as $post)
                    <div
                        class="p-4 bg-gray-100 rounded-lg shadow hover:shadow-md transition flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">{{ $post->title }}</h4>
                            <p class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                        </div>

                        <!-- จำนวนไลค์, บุ๊คมาร์ค และ Read More -->
                        <div class="flex items-center space-x-4 text-gray-600">
                            <!-- จำนวนไลค์ -->
                            <div class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                    class="w-5 h-5 text-red-500">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                                <span>{{ optional($post->likes)->count() ?? 0 }}</span>
                            </div>

                            <!-- จำนวนบุ๊คมาร์ค -->
                            <div class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                    class="w-5 h-5 text-blue-500">
                                    <path d="M6 2a2 2 0 00-2 2v18l8-5.5L20 22V4a2 2 0 00-2-2H6z" />
                                </svg>
                                <span>{{ optional($post->favorites)->count() ?? 0 }}</span>
                            </div>

                            <!-- ปุ่ม Read More -->
                            <a href="{{ route('user.posts.detail', $post->id) }}"
                                class="text-blue-500 flex items-center space-x-1">
                                <span>Read More</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No posts available</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
