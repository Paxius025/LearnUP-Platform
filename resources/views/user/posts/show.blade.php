<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow mt-[120px]">
        <!-- 🔹 แสดงชื่อผู้เขียน -->
        <div class="text-sm text-gray-500 mb-4">
            <p>By <strong>{{ $post->user->name }}</strong></p> <!-- ชื่อผู้เขียน -->
        </div>

        <h2 class="text-3xl font-bold">{{ $post->title }}</h2>
        <p class="text-gray-600 mt-2">{!! $post->content !!}</p>

        @if ($post->pdf_file)
            <div class="mt-4">
                <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank" class="text-blue-600 hover:underline">
                    📄 View PDF
                </a>
            </div>
        @endif

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('user.posts.edit', $post->id) }}" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                ✏️ Edit
            </a>

            <form action="{{ route('user.posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    🗑️ Delete
                </button>
            </form>

            <!-- ฟอร์มแสดงความคิดเห็น -->
            <form action="{{ route('comment.store', $post->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="content" rows="3" class="w-full p-3 border rounded-lg" placeholder="Write a comment..." required></textarea>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Post Comment</button>
            </form>
            <!-- แสดงความคิดเห็น -->
            @foreach ($post->comments()->whereNull('parent_id')->latest()->get() as $comment)
            <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                <p class="font-bold">{{ $comment->user->name }}</p>
                <p class="text-gray-600">{{ $comment->content }}</p>

                <!-- ปุ่มลบคอมเมนต์ -->
                @if (auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                @endif

                <!-- ปุ่มตอบกลับ -->
                <button onclick="showReplyForm({{ $comment->id }})" class="text-blue-600 hover:underline">Reply</button>

                <!-- ฟอร์มตอบกลับ -->
                <form action="{{ route('comment.store', $post->id) }}" method="POST" id="reply-form-{{ $comment->id }}" class="hidden mt-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content" rows="2" class="w-full p-2 border rounded-lg" placeholder="Write a reply..." required></textarea>
                    <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg">Reply</button>
                </form>

                <!-- แสดงการตอบกลับ -->
                @foreach ($comment->replies as $reply)
                    <div class="ml-8 mt-2 p-3 bg-gray-200 rounded-lg">
                        <p class="font-bold">{{ $reply->user->name }}</p>
                        <p class="text-gray-600">{{ $reply->content }}</p>
                    </div>
                @endforeach
            </div>
            @endforeach

        </div>
    </div>

</body>
<script>
    function showReplyForm(commentId) {
        document.getElementById("reply-form-" + commentId).classList.toggle("hidden");
    }
</script>

</html>
