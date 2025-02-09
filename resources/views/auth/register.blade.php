<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Learn Up</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Register</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Full Name</label>
                <input type="text" id="name" name="name" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 border rounded-lg" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-gray-600">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
        </p>
    </div>

</body>
</html>
