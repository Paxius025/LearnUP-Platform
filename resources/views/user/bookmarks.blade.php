@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow mt-[100px]">
    <h2 class="text-3xl font-bold mb-4 ">📌 Bookmarked Posts</h2>

    @if ($bookmarkedPosts->isEmpty())
        <p class="text-gray-500">You haven't bookmarked any posts yet.</p>
    @else
        @foreach ($bookmarkedPosts as $post)
            <div class="border-b py-4">
                <a href="{{ route('user.posts.detail', $post->id) }}" class="text-xl font-semibold text-blue-600 hover:underline">
                    {{ $post->title }}
                </a>
                <p class="text-gray-500 text-sm">Published on {{ $post->created_at->format('M d, Y') }}</p>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $bookmarkedPosts->links() }}
        </div>
    @endif
</div>
@endsection
