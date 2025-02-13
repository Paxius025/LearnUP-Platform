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

    <!-- Content -->
    <div class="max-w-[800px] mx-auto mt-[100px]">

        @foreach ($mostLikedPosts as $post)
            <div class="bg-white border border-gray-300 rounded-lg overflow-hidden mb-6">
                <div class="p-4">
                    <h3 class="text-lg font-bold">{{ $post->title }}</h3>
                    <p class="text-gray-600">Likes: {{ $post->likes_count }}</p>
                </div>

                <!-- Image -->
                @if (!empty($post->image))
                    @php
                        $images = json_decode($post->image, true);
                        $firstImage = !empty($images) && is_array($images) ? $images[0] : null;
                    @endphp
                    @if ($firstImage)
                        <img src="{{ asset('storage/' . ltrim($firstImage, '/')) }}" alt="Post Image" class="max-w-[800px] h-50 object-cover">
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

                <div class="p-4">
                    <a href="{{ route('user.posts.detail', $post->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold py-2 px-4 rounded-lg border-2 border-blue-600 hover:bg-blue-100 transition duration-300 ease-in-out">
                        Read More
                    </a>
                </div>
            </div>
        @endforeach

        @if ($mostLikedPosts->isEmpty())
            <p class="text-gray-500 text-center mt-4">No liked posts available.</p>
        @endif
    </div>
</body>
</html>
