<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learn Up')</title>
    @vite('resources/css/app.css')

    <style>
        /* ป้องกัน Scrollbar เกินจำเป็น */
        html,
        body {
            overflow: hidden;
        }
    </style>
</head>

<body class="h-screen flex flex-col">

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Content -->
    <div class="flex-1 overflow-auto">
        @yield('content')
    </div>

</body>

</html>
