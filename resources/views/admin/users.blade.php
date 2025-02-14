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

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Manage Users</h2>

            <a href="{{ route('admin.users.create') }}"
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                ➕ Create User
            </a>
        </div>


        <!-- 🔍 Search and Role Filter Form -->
        <form id="filterForm" class="bg-white p-2 rounded-lg flex items-center gap-2 justify-between flex-wrap">

            <!-- 🔎 Search Input -->
            <div class="relative flex-shrink-0 w-full sm:w-[640px]">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring focus:ring-green-300 focus:outline-none text-gray-700 transition-all duration-300">

                <button id="clearButton"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition hidden">
                    ✖
                </button>
            </div>

            <!-- 🏷️ Role Filter -->
            <div class="flex items-center gap-4 pr-12">
                @foreach (['user', 'writer', 'admin'] as $role)
                    <label class="flex items-center space-x-1 cursor-pointer">
                        <input type="checkbox" name="roles[]" value="{{ $role }}"
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
                <tbody id="userTable">
                    @foreach ($users as $index => $user)
                        <tr class="border-b border-gray-300 hover:bg-green-50 transition">
                            <td class="p-4">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="p-4 font-bold">{{ $user->name }}</td>
                            <td class="p-4">{{ $user->email }}</td>
                            <td class="p-4">{{ ucfirst($user->role) }}</td>
                            <td class="p-4 flex justify-center space-x-6">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                                    ✏️ Edit
                                </a>
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
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4" id="pagination">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const roleFilters = document.querySelectorAll(".role-filter");
            const userTable = document.getElementById("userTable");
            const paginationDiv = document.getElementById("pagination");

            function fetchUsers() {
                let query = searchInput.value;
                let selectedRoles = Array.from(roleFilters)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value)
                    .join(',');

                let url = `{{ route('admin.users.search') }}?search=${query}&roles=${selectedRoles}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        userTable.innerHTML = "";
                        paginationDiv.innerHTML = "";

                        if (data.users.length === 0) {
                            userTable.innerHTML =
                                '<tr><td colspan="5" class="text-center p-6 text-gray-500">No users found.</td></tr>';
                            return;
                        }

                        data.users.forEach((user, index) => {
                            let row = `
                                <tr class="border-b border-gray-300 hover:bg-green-50 transition">
                                    <td class="p-4">${index + 1}</td>
                                    <td class="p-4 font-bold">${user.name}</td>
                                    <td class="p-4">${user.email}</td>
                                    <td class="p-4">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                                    <td class="p-4 flex justify-center space-x-6">
                                        <a href="/admin/users/${user.id}/edit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">✏️ Edit</a>
                                        <form action="/admin/users/${user.id}/delete" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                            userTable.innerHTML += row;
                        });

                        // Pagination
                        if (data.pagination) {
                            paginationDiv.innerHTML = data.pagination;
                        }
                    });
            }

            searchInput.addEventListener("input", function() {
                if (searchInput.value.length > 0) {
                    clearButton.classList.remove("hidden"); // Show button
                } else {
                    clearButton.classList.add("hidden"); // Hide button
                }
                fetchUsers();
            });

            roleFilters.forEach(checkbox => {
                checkbox.addEventListener("change", fetchUsers);
            });

            // When the clear button is clicked
            clearButton.addEventListener("click", function() {
                searchInput.value = ""; // Clear search value
                clearButton.classList.add("hidden"); // Hide button
                fetchUsers(); // Reload data
            });

            fetchUsers(); // Load initial data
        });
    </script>

</body>

</html>
