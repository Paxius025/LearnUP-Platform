<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logs - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

    @include('components.navbar')

    <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold">System Logs</h2>

        <table class="w-full mt-4 border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">User</th>
                    <th class="border p-2">Action</th>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr class="border">
                        <td class="border p-2">{{ $log->id }}</td>
                        <td class="border p-2">{{ $log->user->name ?? 'System' }}</td>
                        <td class="border p-2">{{ $log->action }}</td>
                        <td class="border p-2">{{ $log->description }}</td>
                        <td class="border p-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>

</body>
</html>
