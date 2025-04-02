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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50 px-6">
    @include('components.navbar')

    <div class="max-w-[800px] mx-auto grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">

        @foreach ($posts as $post)
            @php
                $hasImage = !empty($post->image); 
                $firstImage = $hasImage ? $post->image : null; 

                $isLiked = \App\Models\Like::where('user_id', Auth::id())->where('post_id', $post->id)->exists();
            @endphp

            <div
                class="bg-white border border-gray-300 rounded-lg overflow-hidden flex flex-col relative mb-6
                {{ $hasImage ? 'min-h-[500px]' : 'min-h-[100px]' }}">


                <div class="p-4">
                    <h3 class="text-lg font-bold line-clamp-2">{{ $post->title }}</h3>
                </div>

                @if ($hasImage && $firstImage)
                    <div class="flex justify-center items-center">
                        <img src="{{ asset('storage/' . ltrim($firstImage, '/')) }}" alt="Post Image"
                            class="max-w-[800px] h-50 object-cover">
                    </div>
                @endif
                     <div class="p-4 mt-auto flex justify-between items-center">
                        <button onclick="showLoginAlert()"
                            class="text-blue-600 hover:text-blue-800 hover:underline font-semibold py-2 px-4 rounded-lg border-2 border-blue-600 hover:bg-blue-100 transition duration-300 ease-in-out">
                                Read More
                        </button>
                    </div>
               
            </div>
        @endforeach
    
        @if ($posts->isEmpty())
            <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
        @endif
    </div>
    
</body>
<script>
    function showLoginAlert() {
        Swal.fire({
            title: "Please Login",
            text: "You need to login to read more of this post.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Login Now",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}"; // ไปที่หน้า Login
            }
        });
    }
</script>

</html>
