<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Post - Learn Up</title>
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <script>
        function showToast(message, color) {
            const toast = document.createElement("div");
            toast.textContent = message;
            toast.className = `fixed top-5 right-5 px-4 py-3 rounded-lg text-white text-sm shadow-lg ${color}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</head>

<body class="bg-green-50 min-h-screen font-poppins flex flex-col items-center pt-20">

    <!-- Navbar (Full Width & Fixed) -->
    <div class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        @include('components.navbar')
    </div>

    <div class="max-w-4xl w-full mt-10 bg-white p-8 rounded-lg shadow-xl border border-gray-200">
        <!-- Badge for Post Status -->
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-green-700">{{ $post->title }}</h2>
            <span
                class="px-3 py-1 rounded-full text-white text-sm font-semibold 
                {{ $post->status == 'pending' ? 'bg-yellow-500' : ($post->status == 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                {{ ucfirst($post->status) }}
            </span>
        </div>

        <p class="text-gray-500 text-sm mt-2">
            Submitted by: <span class="font-semibold">{{ $post->user->name }}</span> |
            <span class="text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
        </p>

        <!-- Post Content -->
        <div class="mt-6 text-gray-700 leading-relaxed border-t pt-4">
            <p>{!! nl2br(e($post->content)) !!}</p>
        </div>

        <!-- PDF Attachment -->
        @if ($post->pdf_file)
            <div class="mt-6 flex justify-center">
                <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 flex items-center">
                    📄 View PDF
                </a>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-8 flex space-x-6 justify-center">
            <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST"
                onsubmit="showToast('✅ Post Approved!', 'bg-green-500')">
                @csrf
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-3 rounded-xl shadow-md hover:bg-green-700 transition-all duration-300">
                    ✅ Approve
                </button>
            </form>

            <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST"
                onsubmit="showToast('❌ Post Rejected!', 'bg-red-500')">
                @csrf
                <button type="submit"
                    class="bg-red-600 text-white px-6 py-3 rounded-xl shadow-md hover:bg-red-700 transition-all duration-300">
                    ❌ Reject
                </button>
            </form>
        </div>

        <!-- Back to Dashboard Button -->
        <div class="mt-8 flex justify-center">
            <a href="{{ route('admin.dashboard') }}"
                class="px-5 py-3 bg-gray-100 text-green-700 rounded-lg shadow-md hover:bg-gray-200 transition-all duration-300 flex items-center">
                ← Back to Dashboard
            </a>
        </div>
    </div>

</body>

</html>
