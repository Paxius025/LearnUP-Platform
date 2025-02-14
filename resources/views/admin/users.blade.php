<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Learn Up</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>

<body class="bg-green-50 min-h-screen">

    @include('components.navbar')

    <div class="max-w-5xl mx-auto mt-[80px] px-4">

        <!-- 🔍 Search and Role Filter Form -->
        <form id="filterForm" action="{{ route('admin.users') }}" method="GET"
            class="bg-white p-2 rounded-lg flex items-center gap-2 justify-between flex-wrap">

            <!-- 🔎 Search Input -->
            <div class="relative flex-shrink-0 w-full sm:w-[640px]">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring focus:ring-green-300 focus:outline-none text-gray-700 transition-all duration-300">
            </div>

            <!-- 🏷️ Role Filter -->
            <div class="flex items-center gap-4 pr-12">
                @foreach (['user', 'writer', 'admin'] as $role)
                    <label class="flex items-center space-x-1 cursor-pointer">
                        <input type="checkbox" name="roles[]" value="{{ $role }}"
                            {{ in_array($role, request('roles', [])) ? 'checked' : '' }}
                            class="role-filter flex-shrink-0 w-4 h-4 border-gray-400 rounded-md focus:ring-green-400">
                        <span class="text-gray-700 text-sm">{{ ucfirst($role) }}</span>
                    </label>
                @endforeach
            </div>
        </form>

        <div class="mt-6">
            <table class="w-full bg-white shadow-lg rounded-lg overflow-hidden">
                <thead class="bg-green-100">
                    <tr class="text-left text-gray-700">
                        <th class="p-4">#</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr class="border-b border-gray-300 hover:bg-green-50 transition">
                            <td class="p-4">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="p-4 font-bold">{{ $user->name }}</td>
                            <td class="p-4">{{ $user->email }}</td>
                            <td class="p-4">{{ ucfirst($user->role) }}</td>
                            <td class="p-4 flex justify-center space-x-6">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                                    ✏️ Edit
                                </a>
                                <!-- Delete Form -->
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-6">No users found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Automatically submit the form when a role checkbox is selected
        document.querySelectorAll('.role-filter').forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });

        // Function to clear all filters and reload the page
        function clearFilters() {
            window.location.href = "{{ route('admin.users') }}";
        }
    </script>

</body>

</html>
