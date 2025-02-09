<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-3xl font-bold">Notifications</h2>

        @foreach ($notifications as $notification)
            <div class="p-4 border-b {{ $notification->is_read ? 'bg-gray-200' : 'bg-yellow-100' }}">
                <p>{{ $notification->message }}</p>
                <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>

                @if (!$notification->is_read)
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-lg">Mark as Read</button>
                    </form>
                @endif
            </div>
        @endforeach

        @if ($notifications->isEmpty())
            <p class="text-gray-500 text-center mt-4">No notifications yet.</p>
        @endif
    </div>

</body>
</html>
