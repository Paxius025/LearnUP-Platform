<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Post - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-green-50 min-h-screen font-poppins">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-green-700">{{ $post->title }}</h2>
        <p class="text-gray-500 text-sm">Submitted by: {{ $post->user->name }} | {{ $post->created_at->format('M d, Y') }}</p>

        <div class="mt-6">
            <p class="text-gray-700">{!! $post->content !!}</p>
        </div>

        @if ($post->pdf_file)
            <div class="mt-6">
                <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300">
                    📄 View PDF
                </a>
            </div>
        @endif

        <div class="mt-8 flex space-x-6">
            <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-300">
                    ✅ Approve
                </button>
            </form>

            <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-all duration-300">
                    ❌ Reject
                </button>
            </form>
        </div>

        <div class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="text-green-600 hover:underline">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
