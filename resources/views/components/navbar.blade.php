<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

<nav class="bg-green-600 p-4 text-white flex items-center fixed top-0 w-full z-50 shadow-lg">
    <!-- Logo -->
    <div class="flex-1 flex items-center space-x-4">
        <a href="{{ route('home') }}" class="text-white px-6 py-2 rounded-lg font-semibold flex items-center">
            <i data-lucide="book-open"></i> <span class="ml-2">LearnUP</span>
        </a>
    </div>

    @if (auth()->check())
        <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-6 text-base">
            @php
                $navLinks = [
                    'admin.dashboard' => ['icon' => 'home', 'label' => 'Admin Dashboard'],
                    'admin.manage.posts' => ['icon' => 'file-text', 'label' => 'Manage Posts'],
                    'admin.users' => ['icon' => 'users', 'label' => 'Manage Users'],
                    'admin.stat' => ['icon' => 'bar-chart', 'label' => 'Statistics'],
                    'user.dashboard' => ['icon' => 'home', 'label' => 'Dashboard'],
                    'most.liked.posts' => ['icon' => 'heart', 'label' => 'Most Liked Posts'],
                    'user.posts.create' => ['icon' => 'pencil', 'label' => 'Create Post'],
                    'user.posts.index' => ['icon' => 'book', 'label' => 'My Posts'],
                ];
            @endphp

            @foreach ($navLinks as $route => $data)
                @if (auth()->user()->role === 'admin' && str_starts_with($route, 'admin'))
                    <a href="{{ route($route) }}"
                        class="flex flex-col items-center transition duration-300 transform hover:scale-105 font-semibold
                            {{ request()->routeIs($route) ? 'text-white font-bold' : 'text-white text-opacity-50 hover:text-opacity-100' }}">
                        <i data-lucide="{{ $data['icon'] }}" class="mb-1"></i>
                        <span>{{ $data['label'] }}</span>
                    </a>
                @elseif (auth()->user()->role !== 'admin' && !str_starts_with($route, 'admin'))
                    <a href="{{ route($route) }}"
                        class="flex flex-col items-center transition duration-300 transform hover:scale-105 font-semibold
                            {{ request()->routeIs($route) ? 'text-white font-bold' : 'text-white text-opacity-50 hover:text-opacity-100' }}">
                        <i data-lucide="{{ $data['icon'] }}" class="mb-1"></i>
                        <span>{{ $data['label'] }}</span>
                    </a>
                @endif
            @endforeach

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
                class="relative flex flex-col items-center hover:text-opacity-100 transition duration-300 transform hover:scale-105 font-semibold
                    {{ request()->routeIs('notifications.index') ? 'text-white font-bold' : 'text-white text-opacity-50' }}">
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
                class="text-white px-4 py-2 rounded-lg font-semibold flex items-center hover:text-opacity-100 transition duration-300 transform hover:scale-105 font-semibold
                    {{ request()->routeIs('profile.edit') ? 'text-white font-bold' : 'text-white text-opacity-50' }}">
                <i data-lucide="settings"></i> <span class="ml-1">Profile</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="text-white text-opacity-50 px-4 py-2 rounded-lg font-semibold flex items-center transition duration-300 transform hover:text-opacity-100 hover:scale-105">
                    <i data-lucide="log-out"></i>
                    <span class="ml-1">Logout</span>
                </button>
            </form>
        </div>
    @else
        <div class="flex-1 flex justify-end space-x-4 pr-8">
            <a href="{{ route('login') }}"
                class="flex flex-row items-center space-x-2 transition duration-300 transform hover:scale-105 font-semibold
                    {{ request()->routeIs('login') ? 'text-white font-bold' : 'text-white text-opacity-50 hover:text-opacity-100' }}">
                <i data-lucide="log-in"></i>
                <span>Login</span>
            </a>
        </div>
    @endif
</nav>

<!-- Lucide Icons Script -->
<script>
    lucide.createIcons();
</script>
