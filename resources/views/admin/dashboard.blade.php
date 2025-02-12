<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Learn Up</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-green-50 min-h-screen">

    @include('components.navbar')

    <div class="max-w-6xl mx-auto mt-10">
        <h2 class="text-3xl font-bold text-green-700">Admin Dashboard</h2>

        <!-- Section: Summary -->
        <div class="grid grid-cols-4 gap-6 mt-6">
            <x-dashboard-stat-card title="Total Posts" count="{{ $totalPosts }}" color="green" icon="📌" />
            <x-dashboard-stat-card title="Approved Posts" count="{{ $approvedPosts }}" color="blue" icon="✅" />
            <x-dashboard-stat-card title="Pending Approval" count="{{ $pendingCount }}" color="yellow" icon="⏳" />
            <x-dashboard-stat-card title="Rejected Posts" count="{{ $rejectedPosts }}" color="red" icon="❌" />
        </div>

        <!-- Section: Pending Posts -->
        <h3 class="text-2xl font-bold text-green-700 mt-10">📝 Pending Posts</h3>

        <div class="mt-6">
            <table class="w-full bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
                <thead class="bg-green-200">
                    <tr class="text-left text-gray-700 font-semibold">
                        <th class="p-4 w-12">#</th>
                        <th class="p-4">Title</th>
                        <th class="p-4">Author</th>
                        <th class="p-4">Submitted On</th>
                        <th class="p-4 text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPosts as $index => $post)
                        <tr class="border-b border-gray-300 hover:bg-green-50 transition-all duration-300">
                            <td class="p-4 text-center font-semibold text-gray-600">{{ $index + 1 }}</td>
                            <td class="p-4 font-bold text-green-700">{{ $post->title }}</td>
                            <td class="p-4 text-gray-600">{{ $post->user->name }}</td>
                            <td class="p-4 text-gray-500">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="p-4 text-center w-32">
                                <a href="{{ route('admin.posts.detail', $post->id) }}"
                                    class="inline-flex items-center justify-center bg-green-600 text-white px-4 py-2 rounded-xl shadow-md hover:bg-green-700 transition-all duration-300 whitespace-nowrap">
                                    🔍 Review
                                </a>
                            </td>

                        </tr>
                    @endforeach

                    @if ($pendingPosts->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-6">
                                😴 No posts pending approval.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>


</body>

</html>
