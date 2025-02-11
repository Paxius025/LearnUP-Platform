<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Up - Home</title>
    
    <!-- เพิ่มการใช้ฟอนต์ Sarabun หรือ Kanit จาก Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-green-50 flex flex-col items-center justify-center min-h-screen">

    <div class="text-center max-w-lg mx-auto">
        <!-- เปลี่ยนฟอนต์ของข้อความใหญ่สุด -->
        <h1 class="text-5xl font-bold text-green-700 mb-6 font-[Sarabun]">Welcome to Learn Up</h1>
        <p class="text-gray-700 mb-8 text-lg font-[Sarabun]">แพลตฟอร์มที่ให้คุณแชร์ไฟล์ PDF และโพสต์ความรู้เพื่อการพัฒนา</p>
        
        <p class="text-xl text-green-800 mb-6 italic font-[Sarabun]">"การเรียนรู้ไม่มีที่สิ้นสุด จงแบ่งปันความรู้ให้โลกได้เติบโต"</p>

        <div class="space-x-6">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 text-white rounded-lg shadow-lg hover:bg-green-700 transition-all duration-300 font-[Sarabun]">Register</a>
            <a href="{{ route('login') }}" class="px-8 py-4 bg-gray-600 text-white rounded-lg shadow-lg hover:bg-gray-700 transition-all duration-300 font-[Sarabun]">Login</a>
        </div>
    </div>
  
</body>
</html>
