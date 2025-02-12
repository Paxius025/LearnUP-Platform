<nav class="bg-gradient-to-r from-green-600 to-teal-600 p-4 text-white flex justify-between items-center shadow-2xl">
    <div class="flex items-center space-x-4">
        <!-- Back Button or Logo -->
        @if (!request()->routeIs('user.dashboard') && !request()->routeIs('admin.dashboard'))
            <a href="{{ url()->previous() }}"
                class="bg-gray-700 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-800 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                ⬅️ <span class="ml-2">Back</span>
            </a>
        @else
            <a href="#"
                class="bg-gray-700 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-800 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                📚 <span class="ml-2">LearnUP</span>
            </a>
        @endif
    </div>

    <!-- Navigation Links -->
    <div class="space-x-6 flex items-center text-base">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                🏠 <span class="ml-2">Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                👥 <span class="ml-2">Manage Users</span>
            </a>
            <a href="{{ route('admin.manage.posts') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                📝 <span class="ml-2">Manage Posts</span>
            </a>
            <a href="{{ route('admin.stat') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                📊 <span class="ml-2">Statistics</span>
            </a>
        @else
            <a href="{{ route('user.dashboard') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                🏠 <span class="ml-2">Dashboard</span>
            </a>
            <a href="{{ route('most.liked.posts') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                ❤️ <span class="ml-2">Most Liked Posts</span>
            </a>
            <a href="{{ route('user.posts.create') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                📝 <span class="ml-2">Create Post</span>
            </a>
            <a href="{{ route('user.posts.index') }}"
                class="flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                📖 <span class="ml-2">My Posts</span>
            </a>
        @endif

        <!-- Notification Badge -->
        @php
            $unreadCount =
                auth()->user()->role === 'admin'
                    ? \App\Models\Notification::where('is_admin_read', false)->count()
                    : \App\Models\Notification::where('user_id', auth()->id())
                        ->where('is_user_read', false)
                        ->count();
        @endphp

        <a href="{{ route('notifications.index') }}"
            class="relative flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
            🔔 <span class="ml-2">Notifications</span>
            @if ($unreadCount > 0)
                <span
                    class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full -mr-2 -mt-2">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
    </div>

    <!-- Profile and Logout Section -->
    <div class="flex items-center space-x-4">
        @if (request()->routeIs('profile.edit'))
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-red-700 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                    👋🏻 <span class="ml-2">Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('profile.edit') }}"
                class="bg-black text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                ⚙️ <span class="ml-2">Profile</span>
            </a>
        @endif
    </div>
</nav>
