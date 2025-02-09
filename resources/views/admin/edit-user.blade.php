<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-3xl font-bold">Edit User</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700">Role</label>
                <select id="role" name="role" class="w-full p-3 border rounded-lg">
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="writer" {{ $user->role === 'writer' ? 'selected' : '' }}>Writer</option>
                </select>                
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg">Update</button>
        </form>

        <div class="mt-6">
            <a href="{{ route('admin.users') }}" class="text-gray-600 hover:underline">← Back to Users</a>
        </div>
    </div>

</body>
</html>
