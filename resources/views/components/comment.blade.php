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
            <div class="p-4 bg-gray-100 rounded-lg">
                <p class="text-gray-800">
                    <strong>{{ $comment->user->username }} ({{ ucfirst($comment->user->role) }})</strong>: {{ $comment->content }}
                </p>
                <p class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</p>

                <!-- ปุ่มลบ (เฉพาะเจ้าของหรือ Admin) -->
                @if (auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm">❌ Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-gray-500">No comments yet.</p>
    @endif
</div>
</div>
