<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Learn Up</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <style>
        body {
            padding-top: 80px;
        }
    </style>
</head>

<body class=" min-h-screen">

    @include('components.navbar')

    <div class="container mx-auto p-6 ">
        <!-- Section: Summary -->
        <div class="grid grid-cols-4 gap-6 mt-6">
            <x-dashboard-stat-card title="Total Posts" count="{{ $totalPosts }}" color="green" icon="📌" />
            <x-dashboard-stat-card title="Pending Approval" count="{{ $pendingPosts }}" color="yellow" icon="⏳" />
            <x-dashboard-stat-card title="Approved Posts" count="{{ $approvedPosts }}" color="blue" icon="✅" />
            <x-dashboard-stat-card title="Rejected Posts" count="{{ $rejectedPosts }}" color="red" icon="❌" />
        </div>

        <!-- Section: Pending Posts -->
        <h3 class="text-2xl font-bold text-green-700 mt-10">Pending Posts</h3>

        <div class="mt-6">
            <table class="table-fixed w-full bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
                <thead class="bg-green-200">
                    <tr class="text-left text-gray-700 font-semibold">
                        <th class="p-4 w-16 text-center">#</th>
                        <th class="p-4 w-64">Title</th>
                        <th class="p-4 w-40">Author</th>
                        <th class="p-4 w-40">Date Submitted</th>
                        <th class="p-4 w-32">Time Submitted</th>
                        <th class="p-4 w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingCount as $index => $post)
                        <tr class="border-b border-gray-300 hover:bg-green-50 transition-all duration-300">
                            <td class="p-4 text-center font-semibold text-gray-600">
                                {{ ($pendingCount->currentPage() - 1) * $pendingCount->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-4 font-bold text-green-700 truncate max-w-[250px]">
                                {{ $post->title }}
                            </td>
                            <td class="p-4 text-gray-600">{{ $post->user->name }}</td>
                            <td class="p-4 text-gray-500">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="p-4 text-gray-500">{{ $post->created_at->format('h:i A') }}</td>
                            <td class="p-4 text-center w-32">
                                <a href="{{ route('admin.posts.detail', $post->id) }}"
                                    class="inline-flex items-center justify-center bg-green-600 text-white px-4 py-2 rounded-xl shadow-md hover:bg-green-700 transition-all duration-300 whitespace-nowrap">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if ($pendingCount->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 p-6">
                                No posts pending approval.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $pendingCount->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

</body>

</html>
