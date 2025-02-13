<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learn Up')</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">

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
