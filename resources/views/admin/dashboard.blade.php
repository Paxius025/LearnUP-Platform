<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-6xl mx-auto mt-10">
        <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>

        <!-- Section: Summary -->
        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="p-4 bg-white shadow rounded-lg">
                <h3 class="text-lg font-bold">Total Posts</h3>
                <p class="text-gray-600 text-xl">{{ $totalPosts }}</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg">
                <h3 class="text-lg font-bold">Approved Posts</h3>
                <p class="text-gray-600 text-xl">{{ $approvedPosts }}</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg">
                <h3 class="text-lg font-bold">Pending Approval</h3>
                <p class="text-gray-600 text-xl">{{ $pendingCount }}</p>
            </div>
        </div>

        <!-- Section: Pending Posts -->
        <h3 class="text-2xl font-bold text-gray-800 mt-10">Pending Posts</h3>

        <div class="mt-6">
            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200">
                    <tr class="text-left text-gray-700">
                        <th class="p-3">#</th>
                        <th class="p-3">Title</th>
                        <th class="p-3">Author</th>
                        <th class="p-3">Submitted On</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPosts as $index => $post)
                        <tr class="border-b border-gray-300">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-bold">{{ $post->title }}</td>
                            <td class="p-3">{{ $post->user->name }}</td>
                            <td class="p-3">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.posts.detail', $post->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if ($pendingPosts->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-4">No posts pending approval.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
