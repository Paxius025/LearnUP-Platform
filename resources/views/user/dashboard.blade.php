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
    <div class="max-w-4xl mx-auto mt-10">
        <h2 class="text-3xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-600 mt-2">This is your dashboard where you can see all approved posts.</p>

        <!-- แสดงโพสต์ที่ถูกอนุมัติแล้ว -->
        <div class="mt-6 space-y-4">
            @foreach ($posts as $post)
                <div class="p-4 bg-white shadow rounded-lg">
                    <h3 class="text-xl font-bold">{{ $post->title }}</h3>
                    <p class="text-gray-600">{!! Str::limit($post->content, 150) !!}</p>

                    <div class="mt-2 flex justify-between">
                        <a href="{{ route('user.posts.detail', $post->id) }}" class="text-blue-600 hover:underline">Read more</a>
                        <span class="text-gray-500 text-sm">Published on {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    
                </div>
            @endforeach

            @if ($posts->isEmpty())
                <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
            @endif
        </div>

    </div>

</body>
</html>
