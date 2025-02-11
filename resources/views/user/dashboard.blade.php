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

        <form action="{{ route('user.posts.search') }}" method="GET" class="mb-6">
            <div class="flex items-center border border-gray-300 rounded-full p-2 bg-white shadow-lg hover:shadow-xl transition-shadow duration-300">
                
                <!-- Input with icon inside -->
                <div class="relative w-full">
                    <input type="text" name="query" placeholder="Search posts..."
                        class="w-full px-4 py-2 text-lg text-gray-700 placeholder-gray-500 focus:outline-none rounded-full focus:ring-2 focus:ring-blue-500 pr-10 transition duration-300">
                </div>
                <!-- Submit button with round corners -->
                <button type="submit"
                    class="text-white px-6 py-2 rounded-full bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 flex items-center ml-2">
                    <span class="ml-2">🔎</span>
                </button>
            </div>
        </form>

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
                <div class="p-4 mt-auto flex justify-between items-center">
                    <a href="{{ route('user.posts.detail', $post->id) }}"
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
