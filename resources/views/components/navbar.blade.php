<nav class="bg-blue-600 p-4 text-white flex justify-between">
    <h1 class="text-xl font-bold">Learn Up</h1>
    <div class="space-x-4">
        <a href="{{ route('user.dashboard')}}" class="hover:underline">Dashboard</a>
        <a href="{{ route('user.posts.index') }}" class="hover:underline">📖 My Posts</a>
        <a href="{{ route('user.posts.create') }}" class="hover:underline">📝 Create Post</a>
        {{-- <a href="#" class="hover:underline">⚙️ Settings</a> --}}
    </div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</button>
    </form>
</nav>