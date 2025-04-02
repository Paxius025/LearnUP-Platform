<div class="mt-10">
    <h3 class="text-2xl font-semibold mb-4">Comments</h3>

    @auth
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-6">
            @csrf
            <textarea name="content" rows="3" class="w-full border rounded p-2" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Post Comment
            </button>
        </form>
    @else
        <p class="text-gray-500">Please <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a>
            For add comment</p>
    @endauth

    <!-- 🔹 แสดงคอมเมนต์ -->
    <div class="space-y-4">
        @if ($post->comments && $post->comments->isNotEmpty())
            @foreach ($post->comments as $comment)
                <div class="p-4 bg-gray-100 rounded-lg relative" data-id="{{ $comment->id }}">
                    <div class="flex justify-between items-center w-full">
                        <div class="flex items-center space-x-2">
                            <p class="text-gray-800">
                                <a href="{{ route('profile.show', $comment->user->id) }}"
                                    class="font-semibold text-gray-700 hover:underline">
                                    {{ $comment->user->name }}
                                </a>
                                <span class="comment-content">{{ $comment->content }}</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-x-4">
                            <p class="text-gray-500 text-sm whitespace-nowrap mr-4">
                                {{ $comment->created_at->diffForHumans() }}
                            </p>

                            @if (auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                <button onclick="toggleMenu({{ $comment->id }})"
                                    class="text-gray-600 hover:text-gray-800 text-sm">
                                    Edit
                                </button>
                            @endif
                        </div>
                    </div>


                    <!-- เมนูแก้ไขและลบ -->
                    <div id="comment-menu-{{ $comment->id }}"
                        class="hidden absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-32 z-10">
                        <div class="px-4 py-2">
                            <button onclick="showEditForm({{ $comment->id }})"
                                class="w-full text-left text-blue-500 hover:underline text-sm">
                                Edit
                            </button>
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                class="inline w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left text-red-600 hover:underline text-sm">❌
                                    Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- ฟอร์มแก้ไข (ซ่อนอยู่) -->
                    <div id="edit-form-{{ $comment->id }}" class="hidden mt-2">
                        <textarea id="edit-content-{{ $comment->id }}" class="w-full p-2 border rounded">{{ $comment->content }}</textarea>
                        <div class="flex justify-end mt-2 space-x-2">
                            <button onclick="updateComment({{ $comment->id }})"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Save
                            </button>
                            <button onclick="hideEditForm({{ $comment->id }})"
                                class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">No comments yet.</p>
        @endif
    </div>
</div>

<script>
    function toggleMenu(commentId) {
        const menu = document.getElementById(`comment-menu-${commentId}`);
        menu.classList.toggle('hidden');
    }

    function showEditForm(commentId) {
        document.getElementById(`edit-form-${commentId}`).classList.remove('hidden');
    }

    function hideEditForm(commentId) {
        document.getElementById(`edit-form-${commentId}`).classList.add('hidden');
    }

    async function updateComment(commentId) {
        const newContent = document.getElementById(`edit-content-${commentId}`).value;

        try {
            let response = await fetch(`/comments/${commentId}/update`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    content: newContent
                })
            });

            let data = await response.json();
            if (data.success) {
                document.querySelector(`[data-id='${commentId}'] .comment-content`).innerText = data.content;
                hideEditForm(commentId);
            }
        } catch (error) {
            console.error('Error updating comment:', error);
        }
    }
</script>
