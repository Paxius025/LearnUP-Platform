<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

<nav class="bg-green-600 p-4 text-white flex items-center shadow-lg relative">

    <!-- Logo / Back Button -->
    <div class="flex-1 flex items-center space-x-4">
        @if (!request()->routeIs('user.dashboard') && !request()->routeIs('admin.dashboard'))
            <a href="{{ url()->previous() }}" class="text-white px-6 py-2 rounded-lg font-semibold flex items-center">
                <i data-lucide="arrow-left"></i> <span class="ml-2">Back</span>
            </a>
        @else
            <a href="#" class="text-white px-6 py-2 rounded-lg font-semibold flex items-center">
                <i data-lucide="book-open"></i> <span class="ml-2">LearnUP</span>
            </a>
        @endif
    </div>

    <!-- Navigation Links - ใช้ absolute + transform เพื่อให้ตรงกลาง -->
    <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-6 text-base">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="home" class="mb-1"></i> <span>Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="users" class="mb-1"></i> <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.manage.posts') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="file-text" class="mb-1"></i> <span>Manage Posts</span>
            </a>
            <a href="{{ route('admin.stat') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="bar-chart" class="mb-1"></i> <span>Statistics</span>
            </a>
        @else
            <a href="{{ route('user.dashboard') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="home" class="mb-1"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('most.liked.posts') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="heart" class="mb-1"></i> <span>Most Liked Posts</span>
            </a>
            <a href="{{ route('user.posts.create') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="pencil" class="mb-1"></i> <span>Create Post</span>
            </a>
            <a href="{{ route('user.posts.index') }}"
                class="flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
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
            class="relative flex flex-col items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
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
    <div class="flex-1 flex justify-end space-x-2">
        <a href="{{ route('profile.edit') }}"
            class="text-white px-4 py-2 rounded-lg font-semibold flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
            <i data-lucide="settings"></i> <span class="ml-1">Profile</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="text-white px-4 py-2 rounded-lg font-semibold flex items-center hover:text-teal-200 transition duration-300 transform hover:scale-105 font-semibold">
                <i data-lucide="log-out"></i>
                <span class="ml-1">Logout</span>
            </button>
        </form>
    </div>
</nav>

<!-- Lucide Icons Script -->
<script>
    lucide.createIcons();
</script>
