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
    <div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
        @foreach ($posts as $post)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-72">
                <!-- 🔹 รูปภาพ (แสดงแค่ 1 ภาพ และทำให้มีอัตราส่วน 4:3) -->
                @if ($post->images && count($post->images) > 0)
                    <div class="w-full h-[55%]">
                        <img src="{{ Storage::url($post->images[0]) }}" class="w-full h-full object-cover">

                    </div>
                @endif
    
                <!-- 🔹 เนื้อหาโพสต์ -->
                <div class="p-4 flex-grow flex flex-col">
                    <h3 class="text-lg font-bold line-clamp-2">{{ $post->title }}</h3>
                    <div class="mt-auto flex justify-between items-center">
                        <a href="{{ route('user.posts.detail', $post->id) }}" class="text-blue-600 hover:underline">Read more</a>
                    </div>
                </div>
            </div>
        @endforeach
    
        @if ($posts->isEmpty())
            <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
        @endif
    </div>
    

</body>
</html>
