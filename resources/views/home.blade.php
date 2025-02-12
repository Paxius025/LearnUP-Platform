<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <!-- Import Sarabun or Kanit fonts from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-green-50 flex flex-col items-center justify-center min-h-screen px-4">

    <div class="text-center w-full max-w-6xl mx-auto">
        <!-- Enlarged Heading -->
        <h1 class="text-6xl font-bold text-green-700 mb-8 font-[Sarabun]">Welcome to LearnUP</h1>
        <p class="text-gray-700 mb-10 text-2xl font-[Sarabun]">A platform where you can share PDF files and knowledge posts for continuous learning.</p>
        
        <p class="text-3xl text-green-800 mb-10 italic font-[Sarabun]">"Learning has no limits. Share your knowledge and help the world grow."</p>

        <!-- Register Button -->
        <a href="{{ route('register') }}" class="px-10 py-5 text-2xl bg-green-600 text-white rounded-xl shadow-lg hover:bg-green-700 transition-all duration-300 font-[Sarabun] block w-full max-w-xs mx-auto">Create an Account</a>

        <!-- Login Section -->
        <p class="text-xl text-gray-700 mt-6 font-[Sarabun] flex items-center justify-center">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-green-600 font-bold ml-2 hover:underline">Login</a>
        </p>        
    </div>
  
</body>
</html>
