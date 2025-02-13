<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
</head>

<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <!-- Content -->
    <div class="max-w-[800px] mx-auto mt-[60px] grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 pt-10">

        <form action="{{ route('user.posts.search') }}" method="GET" class="mb-1">
            <div
                class="flex items-center border border-gray-300 rounded-xl p-2 bg-white">

                <div class="relative w-full">
                    <input type="text" name="query" placeholder="Search posts..."
                        class="w-full px-3 py-2 text-lg text-gray-700 placeholder-gray-500 focus:outline-none rounded-full focus:ring-2 focus:ring-blue-500 pr-10 transition duration-300">
                </div>

                <button type="submit"
                    class="text-white px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 flex items-center ml-2">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>


                <a href="{{ route('user.bookmarks') }}"
                    class="ml-2 text-white px-6 py-2 rounded-full bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v18l7-5 7 5V3z" />
                    </svg>
                    
                </a>

            </div>
        </form>

        @foreach ($posts as $post)
            <div class="bg-white border border-gray-300 rounded-lg overflow-hidden flex flex-col min-h-[500px] relative">

                <!-- 🔹 ปุ่ม Bookmark (มุมขวาบน) -->
                <x-bookmark-button :post="$post" />

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

                    <!-- จำนวนไลค์ -->
                    <span id="like-count-{{ $post->id }}" class="text-sm text-gray-500 ml-2 mr-4">
                        {{ $post->likes()->count() }} Likes
                    </span>

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
    @vite(['resources/js/app.js'])

    <script>
        function toggleLike(postId) {
            axios.post(`/like/${postId}`)
                .then(response => {
                    const icon = document.getElementById(`like-icon-${postId}`);
                    const likeCount = document.getElementById(`like-count-${postId}`);

                    // เปลี่ยนสีของ Like โดยใช้ CSS Class
                    if (response.data.liked) {
                        icon.classList.remove("text-gray-600");
                        icon.classList.add("text-blue-600"); // สีเมื่อไลค์แล้ว
                    } else {
                        icon.classList.remove("text-blue-600");
                        icon.classList.add("text-gray-600"); // สีปกติ
                    }

                    // อัพเดทจำนวนไลค์
                    likeCount.textContent = `${response.data.likeCount} Likes`;

                    // เพิ่ม Animation
                    icon.classList.add("scale-110");
                    setTimeout(() => {
                        icon.classList.remove("scale-110");
                    }, 200);
                })
                .catch(error => {
                    console.error('There was an error!', error);
                    alert('Something went wrong. Please try again later.');
                });
        }
    </script>
</body>

</html>
