<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Learn Up</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="relative max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">

        <div class="flex items-center justify-end w-full">
            <!-- แสดงจำนวนไลค์ -->
            <div class="flex items-center mr-10">
                <span id="like-count-{{ $post->id }}" class="text-sm text-gray-500 flex items-center">
                    ❤️ {{ $post->likes()->count() }} Likes
                </span>
            </div>

            <!-- ปุ่ม Bookmark -->
            <div>
                <x-bookmark-button :post="$post" />
            </div>
        </div>


        <h2 class="text-3xl font-bold">{{ $post->title }}</h2>
        <p class="text-gray-500 text-sm">Published on {{ $post->created_at->format('M d, Y') }} by
            <a href="{{ route('profile.show', $post->user->id) }}" class="font-semibold text-gray-700 hover:underline">
                {{ $post->user->name }}
            </a>

        </p>


        <div class="mt-4">
            <p class="text-gray-700">{!! $post->content !!}</p>
        </div>

        @if ($post->pdf_file)
            <div class="mt-6">
                <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    📄 View PDF
                </a>
            </div>
        @endif

        <!-- 🔹 คอมเมนต์ -->
        @include('components.comment', ['post' => $post])

        <div class="mt-6">
            <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:underline">← Back to Dashboard</a>
        </div>
    </div>

    <!-- ✅ JavaScript สำหรับ Bookmark -->
    @vite(['resources/js/app.js'])
</body>

</html>
