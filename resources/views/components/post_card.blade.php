<div class="max-w-[3500px] mx-auto mt-10 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
    <!-- วนลูปแค่โพสต์ที่ส่งมา -->
    <div class="p-8 bg-white shadow-lg rounded-xl border border-gray-300 max-w-[2500px] mx-auto">
        @if (!empty($post->image))
            @php
                // แปลง JSON เป็น Array
                $images = json_decode($post->image, true);
            @endphp
            @if (!empty($images) && is_array($images) && count($images) > 0)
                <img src="{{ asset('storage/' . ltrim($images[0], '/')) }}" alt="Post Image"
                    class="h-48 object-cover rounded-lg shadow-md mb-4">
            @endif
        @endif

        <h3 class="text-2xl font-bold mt-4">
            <a href="{{ route('user.posts.show', $post->id) }}" class="text-blue-600 hover:underline">
                {{ $post->title }}
            </a>
        </h3>

        <p class="text-gray-700 mt-2">{!! Str::limit(strip_tags($post->content), 100) !!}</p>

        <div class="mt-2 flex justify-between items-center">
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
            <div class="ml-5"></div>
            <!-- เพิ่ม space-x-4 ที่ div เพื่อเพิ่มระยะห่างระหว่างสถานะและปุ่ม -->
            <div class="flex space-x-2 mt-2">
                <a href="{{ route('user.posts.edit', $post->id) }}"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition duration-300">
                    ✏️ Edit
                </a>
                <form action="{{ route('user.posts.delete', $post->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition duration-300">
                        🗑️ Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if ($posts->isEmpty())
        <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
    @endif
</div>
