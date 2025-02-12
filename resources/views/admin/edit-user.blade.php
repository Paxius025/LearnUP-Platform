<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Role - Learn Up</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-green-50 min-h-screen font-poppins flex flex-col items-center pt-20">

    <!-- Navbar -->
    <div class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        @include('components.navbar')
    </div>

    <div class="max-w-4xl w-full mt-10 bg-white p-8 rounded-xl shadow-xl border border-gray-200">
        <h2 class="text-3xl font-bold text-green-700 flex items-center">
            ✏️ Edit User Role
        </h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-semibold flex items-center">
                    <span>👤 Name</span>
                </label>
                <input type="text" id="name" name="name" value="{{ $user->name }}"
                    class="w-full p-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed shadow-inner"
                    readonly>
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-semibold flex items-center">
                    <span>📧 Email</span>
                </label>
                <input type="email" id="email" name="email" value="{{ $user->email }}"
                    class="w-full p-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed shadow-inner"
                    readonly>
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label for="role" class="block text-gray-700 font-semibold flex items-center">
                    <span>🔄 Change Role</span>
                </label>
                <select id="role" name="role"
                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-600 shadow-md bg-white">
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>👥 User</option>
                    <option value="writer" {{ $user->role === 'writer' ? 'selected' : '' }}>✍️ Writer</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>🔧 Admin</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 shadow-md text-lg font-semibold flex justify-center items-center">
                ✅ Update Role
            </button>
        </form>

        <!-- Back Button -->
        <div class="mt-6 flex justify-center">
            <a href="{{ route('admin.users') }}"
                class="px-5 py-3 bg-gray-100 text-green-700 rounded-lg shadow-md hover:bg-gray-200 transition-all duration-300 flex items-center">
                ← Back to Users
            </a>
        </div>
    </div>

</body>

</html>
