<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Learn Up</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <!-- Content -->
    <div class="max-w-[800px] mx-auto mt-10 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
        <!-- ปรับความกว้างที่นี่ -->
        @foreach ($posts as $post)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col min-h-[500px]">
                <!-- ความสูงยังคงเท่าเดิม -->

                <!-- 🔹 Title -->
                <div class="p-4">
                    <h3 class="text-lg font-bold line-clamp-2">{{ $post->title }}</h3>
                </div>

                <!-- 🔹 รูปภาพ (ดึง index[0] และกำหนดขนาด 4:3) -->
                @if (!empty($post->image))
                    @php
                        $images = json_decode($post->image, true);
                        $firstImage = !empty($images) && is_array($images) ? $images[0] : null;
                    @endphp
                    @if ($firstImage)
                        <img src="{{ asset('storage/' . ltrim($firstImage, '/')) }}" alt="Post Image"
                            class="max-w-[800px] h-50 object-cover">
                    @endif
                @endif

                <!-- 🔹 Read More Button -->
                <div class="p-4 mt-auto">
                    <a href="{{ route('user.posts.detail', $post->id) }}" class="text-blue-600 hover:underline">Read
                        more</a>
                </div>

            </div>
        @endforeach

        @if ($posts->isEmpty())
            <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
        @endif
    </div>


</body>

</html>
