<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logs - Learn Up</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>

<body class="min-h-screen">

    @include('components.navbar')

    <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg mt-[100px]">
        <h2 class="text-2xl font-bold text-green-700">System Logs</h2>

        <table class="w-full mt-4 border-collapse border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-green-100">
                <tr>
                    <th class="border p-3 text-left">#</th>
                    <th class="border p-3 text-left">User</th>
                    <th class="border p-3 text-left">Action</th>
                    <th class="border p-3 text-left">Description</th>
                    <th class="border p-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr class="border-b border-gray-300">
                        <td class="border p-3">{{ $log->id }}</td>
                        <td class="border p-3">{{ $log->user->name ?? 'System' }}</td>
                        <td class="border p-3">{{ $log->action }}</td>
                        <td class="border p-3">{{ $log->description }}</td>
                        <td class="border p-3">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
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
