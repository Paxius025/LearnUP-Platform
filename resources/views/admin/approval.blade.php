<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Post - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-3xl font-bold">{{ $post->title }}</h2>
        <p class="text-gray-500 text-sm">Submitted by: {{ $post->user->name }} | {{ $post->created_at->format('M d, Y') }}</p>

        <div class="mt-4">
            <p class="text-gray-700">{!! $post->content !!}</p>
        </div>

        @if ($post->pdf_file)
            <div class="mt-6">
                <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    📄 View PDF
                </a>
            </div>
        @endif

        <div class="mt-6 flex space-x-4">
            <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                    ✅ Approve
                </button>
            </form>

            <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                    ❌ Reject
                </button>
            </form>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:underline">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
