<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post List - Learn Up</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Content -->
    <div class="max-w-screen-lg mx-auto mt-5 overflow-x-auto mt-[100px]">
        <table class="table-auto w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[200px]">Title</th>
                    <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[200px]">Content</th>
                    <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[100px]">Date</th>
                    <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[100px]">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[300px]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr class="border-t border-gray-200">
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('user.posts.show', $post->id) }}" class="text-blue-600 hover:underline">
                                {{ Str::limit($post->title, 15) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700">
                            {!! Str::limit(strip_tags($post->content), 15) !!}
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500 text-sm">
                            📅 {{ $post->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($post->status === 'approved')
                                <span class="text-green-600 font-bold">Approved</span>
                            @elseif ($post->status === 'pending')
                                <span class="text-yellow-600 font-bold">Pending</span>
                            @else
                                <span class="text-red-600 font-bold">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('user.posts.edit', $post->id) }}"
                                    class="px-4 py-2 w-24 bg-yellow-500 text-white rounded-md shadow-md hover:bg-yellow-600 transition duration-300 transform hover:scale-105 flex items-center justify-center gap-1">
                                    ✏️ Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('user.posts.delete', $post->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 w-28 bg-red-600 text-white rounded-md shadow-md hover:bg-red-700 transition duration-300 transform hover:scale-105 flex items-center justify-center gap-1">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>

        @if ($posts->isEmpty())
            <p class="text-gray-500 text-center mt-4">No approved posts available.</p>
        @endif
    </div>

</body>

</html>