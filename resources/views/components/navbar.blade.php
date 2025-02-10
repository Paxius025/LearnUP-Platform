<nav class="bg-green-600 p-4 text-white flex justify-between items-center">
    <h1 class="text-xl font-bold">Learn Up</h1>
    
    <div class="space-x-6 flex items-center">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">🏠 Admin Dashboard</a>
            <a href="{{ route('admin.logs') }}" class="hover:underline">📜 Logs</a>
            <a href="{{ route('admin.users') }}" class="hover:underline">👥 Manage Users</a>
        @else
            <a href="{{ route('user.dashboard') }}" class="hover:underline">🏠 Dashboard</a>
            <a href="{{ route('user.posts.create') }}" class="hover:underline">📝 Create Post</a>
            <a href="{{ route('user.posts.index') }}" class="hover:underline">📖 My Posts</a>
        @endif

        <!-- 🔔 Notification Badge -->
        @php
            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                       ->where('is_read', false)
                                       ->count();
        @endphp
        <a href="{{ route('notifications.index') }}" class="relative hover:underline">
            🔔 Notifications
            @if ($unreadCount > 0)
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</button>
    </form>    
</nav>
