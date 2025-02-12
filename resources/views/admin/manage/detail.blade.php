@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold mb-4">📌 Post Details</h1>

            <p><strong>Title:</strong> {{ $post->title }}</p>
            <p><strong>Content:</strong></p>
            <div class="border p-4 rounded-lg bg-gray-100">
                {!! $post->content !!}
            </div>

            <p><strong>Status:</strong>
                @if ($post->status == 'pending')
                    <span class="text-yellow-500">Pending Approval</span>
                @elseif ($post->status == 'approved')
                    <span class="text-green-500">Approved</span>
                @else
                    <span class="text-red-500">Rejected</span>
                @endif
            </p>

            <p><strong>Posted by:</strong> {{ $post->user->name }}</p>
            <p><strong>Created at:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</p>

            <div class="mt-4 flex space-x-4">
                @if ($post->status == 'pending')
                    <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition-all duration-300">
                            ✅ Approve
                        </button>
                    </form>

                    <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 transition-all duration-300">
                            ❌ Reject
                        </button>
                    </form>

                @elseif ($post->status == 'approved')
                    <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 transition-all duration-300">
                            ❌ Reject
                        </button>
                    </form>

                @elseif ($post->status == 'rejected')
                    <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition-all duration-300">
                            ✅ Approve
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.manage.posts') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600 transition-all duration-300">
                    ⬅️ Back to Manage Posts
                </a>
            </div>
        </div>
    </div>
@endsection
