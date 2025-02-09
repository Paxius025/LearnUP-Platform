<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" name="remember" class="mr-2">
                <label for="remember" class="text-gray-700">Remember Me</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">
                Login
            </button>
        </form>

        <p class="mt-4 text-center text-gray-600">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
        </p>
    </div>

</body>
</html>
