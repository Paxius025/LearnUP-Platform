<div class="mt-10">
    <h3 class="text-2xl font-semibold mb-4">💬 Comments</h3>

    <!-- 🔹 ฟอร์มเพิ่มคอมเมนต์ -->
    @auth
    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-6">
        @csrf
        <textarea name="content" rows="3" class="w-full border rounded p-2" placeholder="Write a comment..." required></textarea>
        <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
            💬 Post Comment
        </button>
    </form>
    @else
    <p class="text-gray-500">กรุณา <a href="{{ route('login') }}" class="text-blue-500 hover:underline">เข้าสู่ระบบ</a> เพื่อแสดงความคิดเห็น</p>
    @endauth

    <!-- 🔹 แสดงคอมเมนต์ -->
    <div class="space-y-4">
        @if ($post->comments && $post->comments->isNotEmpty())
            @foreach ($post->comments as $comment)
                <div class="p-4 bg-gray-100 rounded-lg" data-id="{{ $comment->id }}">
                    <p class="text-gray-800">
                        <strong>{{ $comment->user->name }} ({{ ucfirst($comment->user->role) }})</strong>:
                        <span class="comment-content">{{ $comment->content }}</span>
                    </p>
                    <p class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</p>

                    <!-- ปุ่มแก้ไขและลบ -->
                    @if (auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                        <div class="mt-2 space-x-2">
                            <button onclick="showEditForm({{ $comment->id }})" class="text-blue-500 hover:underline text-sm">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm">❌ Delete</button>
                            </form>
                        </div>
                    @endif

                    <!-- ฟอร์มแก้ไข (ซ่อนอยู่) -->
                    <div id="edit-form-{{ $comment->id }}" class="hidden mt-2">
                        <textarea id="edit-content-{{ $comment->id }}" class="w-full p-2 border rounded">{{ $comment->content }}</textarea>
                        <div class="flex justify-end mt-2 space-x-2">
                            <button onclick="updateComment({{ $comment->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                💾 Save
                            </button>
                            <button onclick="hideEditForm({{ $comment->id }})" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                                ❌ Cancel
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
            body: JSON.stringify({ content: newContent })
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
