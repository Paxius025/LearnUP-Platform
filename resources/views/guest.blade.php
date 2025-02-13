<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Page - LearnUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            padding-top: 100px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50 px-6">
    @include('components.navbar')

    <div class="max-w-[800px] mx-auto grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 ">

        @foreach ($posts as $post)
            <div
                class="bg-white border border-gray-300 rounded-lg overflow-hidden flex flex-col min-h-[500px] relative">

                <div class="p-4">
                    <h3 class="text-lg font-bold line-clamp-2">{{ $post->title }}</h3>
                </div>

                @if (!empty($post->image))
                    @php
                        $images = json_decode($post->image, true);
                        $firstImage = !empty($images) && is_array($images) ? $images[0] : null;
                    @endphp
                    @if ($firstImage)
                        <img src="{{ asset('storage/' . ltrim($firstImage, '/')) }}" alt="Post Image"
                            class="max-w-[800px] h-50 object-cover">
                    @else
                        <div class="flex justify-center items-center h-[450px] bg-gray-100">
                            <p class="text-black font-bold text-xl">No Image Available</p>
                        </div>
                    @endif
                @else
                    <div class="flex justify-center items-center h-[450px] bg-gray-100">
                        <p class="text-black font-bold text-xl">No Image Available</p>
                    </div>
                @endif
                @php
                    $isLiked = \App\Models\Like::where('user_id', Auth::id())->where('post_id', $post->id)->exists();
                @endphp

                <!-- 🔹 Like & Read More Button -->
                <div class="p-4 mt-auto flex justify-between items-center">
                    <!-- 🔹 ปุ่ม Like -->
                    <button id="like-button-{{ $post->id }}" onclick="toggleLike({{ $post->id }})"
                        class="text-gray-600 hover:text-blue-600 transition-transform duration-300 ease-in-out transform"
                        data-liked="{{ $isLiked ? 'true' : 'false' }}">
                        <i id="like-icon-{{ $post->id }}"
                            class="fas fa-thumbs-up text-2xl {{ $isLiked ? 'text-blue-600' : 'text-gray-600' }}"></i>
                    </button>


                    <a href="#"
                        class="text-blue-600 hover:text-blue-800 hover:underline font-semibold py-2 px-4 rounded-lg border-2 border-blue-600 hover:bg-blue-100 transition duration-300 ease-in-out">
                        Read More
                    </a>
                </div>
            </div>
        @endforeach



        @if ($posts->isEmpty())
            <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
        @endif
    </div>
</body>

</html>
