<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Content -->
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

</body>
</html>
