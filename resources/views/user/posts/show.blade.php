<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
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
        </div>
    </div>

</body>
</html>
