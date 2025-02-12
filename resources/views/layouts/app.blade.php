<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learn Up')</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">

    <style>
        
        /* html,
        body {
            overflow: hidden;
        } */
    </style>
</head>

<body>
    @include('components.navbar')
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

</body>

</html>
