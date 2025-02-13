<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Page - LearnUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50 px-6">
    @include('components.navbar')

    <div
        class="text-center w-full max-w-4xl mx-auto bg-white bg-opacity-95 backdrop-blur-lg rounded-3xl p-12 border border-gray-300 shadow-lg">
        <h1 class="text-4xl font-bold text-gray-700 mb-6 font-[Poppins]">Welcome, Guest! 👤</h1>
        <p class="text-lg text-gray-600 mb-8 font-[Poppins]">You can browse posts but need an account to interact.</p>

        <a href="{{ route('register') }}"
            class="px-6 py-3 text-lg bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 font-[Poppins]">
            🚀 Register Now
        </a>
        <a href="{{ route('home') }}"
            class="px-6 py-3 text-lg bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-300 font-[Poppins] ml-4">
            🔙 Back to Home
        </a>
    </div>
</body>

</html>
