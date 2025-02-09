<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Up - Home</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to Learn Up</h1>
        <p class="text-gray-600 mb-6">แพลตฟอร์มที่ให้คุณแชร์ไฟล์ PDF และโพสต์ความรู้</p>
        
        <div class="space-x-4">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Register</a>
            <a href="{{ route('login') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Login</a>
        </div>
    </div>

</body>
</html>
