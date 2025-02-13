<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    
    <!-- Import Poppins font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-green-50 px-6">

    <div class="text-center w-full max-w-7xl mx-auto bg-white bg-opacity-95 backdrop-blur-lg rounded-3xl p-16 border border-green-300 hover:scale-105 transition-all duration-300">
        <!-- Enlarged Heading -->
        <h1 class="text-5xl md:text-7xl font-bold bg-gradient-to-r from-green-400 to-green-700 text-transparent bg-clip-text mb-6 font-[Poppins] drop-shadow-lg">
            Welcome to <span class="text-green-600">LearnUP</span>
        </h1>

        <p class="text-gray-800 mb-6 text-lg md:text-2xl font-[Poppins] drop-shadow">
            A platform where you can share PDF files and knowledge posts for continuous learning.
        </p>
        
        <p class="text-xl md:text-2xl text-green-700 mb-10 italic font-[Poppins] drop-shadow-lg">
            "Learning has no limits. Share your knowledge and help the world grow."
        </p>

        <!-- Register Button -->
        <a href="{{ route('register') }}" class="px-10 py-5 text-xl md:text-2xl bg-green-700 text-white rounded-full shadow-xl hover:bg-green-800 hover:scale-105 transition-all duration-300 font-[Poppins] block w-full max-w-md mx-auto">
            🚀 Create an Account
        </a>

        <!-- Login Section -->
        <p class="text-lg md:text-xl text-gray-800 mt-6 font-[Poppins] flex items-center justify-center">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-green-500 font-bold ml-2 hover:text-green-600 hover:underline">Login</a>
        </p>        
    </div>
  
</body
