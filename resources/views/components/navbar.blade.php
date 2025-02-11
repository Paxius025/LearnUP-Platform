<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Up</title>
    <!-- Import Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="font-poppins bg-green-50">

    <nav class="bg-gradient-to-r from-green-500 to-teal-500 p-4 text-white flex justify-between items-center shadow-xl">
        <h1 class="text-3xl font-extrabold text-white font-bold">
            Learn Up
        </h1>

        <div class="space-x-8 flex items-center">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    🏠 Admin Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    👥 Manage Users
                </a>
                <a href="{{ route('admin.stat') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    📊 Statistics
                </a>
                <a href="{{ route('admin.logs') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    📜 Logs
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    🏠 Dashboard
                </a>
                <a href="{{ route('user.posts.create') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    📝 Create Post
                </a>
                <a href="{{ route('user.posts.index') }}" class="hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                    📖 My Posts
                </a>
            @endif

            <!-- 🔔 Notification Badge -->
            @php
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
            @endphp
            <a href="{{ route('notifications.index') }}" class="relative flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-bold">
                🔔 Notifications
                @if ($unreadCount > 0)
                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full -mr-2 -mt-2">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-red-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 font-bold">
                Logout
            </button>
        </form>

    </nav>

    <!-- Content goes here -->

</body>

</html>
