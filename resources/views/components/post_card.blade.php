<div class="p-4 bg-white shadow rounded-lg">
    @if ($post->image)
    <p>Image URL: {{ asset('storage/' . $post->image) }}</p>
    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full h-48 object-cover rounded">
    @endif


    <h3 class="text-xl font-bold mt-2">
        <a href="{{ route('user.posts.show', $post->id) }}" class="text-blue-600 hover:underline">
            {{ $post->title }}
        </a>
    </h3>
    <p class="text-gray-600">{!! Str::limit(strip_tags($post->content), 100) !!}</p>

    <div class="mt-2 flex space-x-2">
        <a href="{{ route('user.posts.edit', $post->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
            ✏️ Edit
        </a>

        <form action="{{ route('user.posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                🗑️ Delete
            </button>
        </form>
    </div>
</div>
