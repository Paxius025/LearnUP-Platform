@extends('layouts.app')
@include('components.navbar')

<div class="max-w-[3500px] mx-auto mt-10 overflow-x-auto">
    <table class="table-auto w-full bg-white border border-gray-300 rounded-lg shadow-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Content</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr class="border-t border-gray-200">
                    <td class="px-6 py-4">
                        <a href="{{ route('user.posts.show', $post->id) }}" class="text-blue-600 hover:underline">
                            {{ $post->title }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{!! Str::limit(strip_tags($post->content), 100) !!}</td>
                    <td class="px-6 py-4 text-gray-500 text-sm">
                        📅 {{ $post->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($post->status === 'approved')
                            <span class="text-green-600 font-bold">Approved</span>
                        @elseif ($post->status === 'pending')
                            <span class="text-yellow-600 font-bold">Pending</span>
                        @else
                            <span class="text-red-600 font-bold">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('user.posts.edit', $post->id) }}"
                                class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300 transform hover:scale-105">
                                <span class="mr-2">✏️</span> Edit
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('user.posts.delete', $post->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition duration-300 transform hover:scale-105">
                                    <span class="mr-2">🗑️</span> Delete
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($posts->isEmpty())
        <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
    @endif
</div>
