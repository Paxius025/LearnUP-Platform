<div class="p-6 bg-white shadow-lg rounded-xl border border-gray-300">
    @if ($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full h-48 object-cover rounded-lg shadow-md">
    @endif

    <h3 class="text-2xl font-bold mt-4">
        <a href="{{ route('user.posts.show', $post->id) }}" class="text-blue-600 hover:underline">
            {{ $post->title }}
        </a>
    </h3>
    
    <p class="text-gray-700 mt-2">{!! Str::limit(strip_tags($post->content), 100) !!}</p>

    <div class="mt-4 flex justify-between items-center">
        <span class="text-gray-500 text-sm">
            📅 {{ $post->created_at->format('M d, Y') }} 
            | 🔖 สถานะ: 
            @if ($post->status === 'approved')
                <span class="text-green-600 font-bold">Approved</span>
            @elseif ($post->status === 'pending')
                <span class="text-yellow-600 font-bold">Pending</span>
            @else
                <span class="text-red-600 font-bold">Rejected</span>
            @endif
        </span>
        <div class="flex space-x-2">
            <a href="{{ route('user.posts.edit', $post->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600">
                ✏️ Edit
            </a>
            <form action="{{ route('user.posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>
</div>
