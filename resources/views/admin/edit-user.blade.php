<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Role - Learn Up</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-green-50 min-h-screen font-poppins">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-green-700">Edit User Role</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}"
                    class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                    readonly>
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}"
                    class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                    readonly>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-gray-700">Role</label>
                <select id="role" name="role"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="writer" {{ $user->role === 'writer' ? 'selected' : '' }}>Writer</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-300">Update</button>
        </form>


        <div class="mt-6">
            <a href="{{ route('admin.users') }}" class="text-green-600 hover:underline">← Back to Users</a>
        </div>
    </div>

</body>

</html>
