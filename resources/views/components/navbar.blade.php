<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

<nav class="bg-gradient-to-r from-green-600 to-teal-600 p-4 text-white flex justify-between items-center shadow-2xl">
    <div class="flex items-center space-x-4">
        <!-- Back Button or Logo -->
        @if (!request()->routeIs('user.dashboard') && !request()->routeIs('admin.dashboard'))
            <a href="{{ url()->previous() }}"
                class="bg-gray-700 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-800 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                <i data-lucide="arrow-left"></i> <span class="ml-2">Back</span>
            </a>
        @else
            <a href="#"
                class="bg-gray-700 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-800 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                <i data-lucide="book-open"></i> <span class="ml-2">LearnUP</span>
            </a>
        @endif
    </div>

    <!-- Navigation Links -->
    <div class="space-x-6 flex items-center text-base">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="home" class="mb-1"></i> <span>Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="users" class="mb-1"></i> <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.manage.posts') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="file-text" class="mb-1"></i> <span>Manage Posts</span>
            </a>
            <a href="{{ route('admin.stat') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="bar-chart" class="mb-1"></i> <span>Statistics</span>
            </a>
        @else
            <a href="{{ route('user.dashboard') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="home" class="mb-1"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('most.liked.posts') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="heart" class="mb-1"></i> <span>Most Liked Posts</span>
            </a>
            <a href="{{ route('user.posts.create') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="pencil" class="mb-1"></i> <span>Create Post</span>
            </a>
            <a href="{{ route('user.posts.index') }}"
                class="flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="book" class="mb-1"></i> <span>My Posts</span>
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
            class="relative flex flex-col items-center justify-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
            <i data-lucide="bell" class="mb-1"></i> <span>Notifications</span>
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
                    <i data-lucide="log-out"></i> <span class="ml-2">Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('profile.edit') }}"
                class="bg-black text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 transform hover:scale-105 font-semibold flex items-center">
                <i data-lucide="settings"></i> <span class="ml-2">Profile</span>
            </a>
        @endif
    </div>
</nav>

<!-- Lucide Icons Script -->
<script>
    lucide.createIcons();
</script>