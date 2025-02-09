<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 p-4 text-white flex justify-between">
        <h1 class="text-xl font-bold">Learn Up</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</button>
        </form>
    </nav>

    <div class="max-w-4xl mx-auto mt-10">
        <h2 class="text-3xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-600 mt-2">This is your dashboard where you can manage your posts.</p>

        <div class="mt-6">
            <a href="#" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create New Post</a>
        </div>
    </div>

</body>
</html>
