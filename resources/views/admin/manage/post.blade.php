@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">จัดการโพสต์</h2>

        {{-- สถิติของโพสต์ --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
            <x-dashboard-stat-card title="โพสต์ทั้งหมด" count="{{ $totalPosts }}" color="green" icon="📌" />
            <x-dashboard-stat-card title="รออนุมัติ" count="{{ $pendingCount }}" color="yellow" icon="⏳" />
            <x-dashboard-stat-card title="อนุมัติแล้ว" count="{{ $approvedCount }}" color="blue" icon="✅" />
            <x-dashboard-stat-card title="ถูกปฏิเสธ" count="{{ $rejectedCount }}" color="red" icon="❌" />
        </div>

        {{-- แสดงโพสต์ที่อนุมัติและถูกปฏิเสธ ข้างกัน --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            {{-- ตารางสำหรับ Approved Posts --}}
            @if ($approvedPosts->count() > 0)
                <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col h-full">
                    <h3 class="text-xl font-semibold p-4 bg-gray-100">✅ โพสต์ที่อนุมัติแล้ว</h3>
                    
                    <div class="flex-grow overflow-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                    <th class="p-3 text-left">#</th>
                                    <th class="p-3 text-left">ชื่อโพสต์</th>
                                    <th class="p-3 text-center">วันที่สร้าง</th>
                                    <th class="p-3 text-center">รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvedPosts as $post)
                                    <tr class="border-b hover:bg-gray-100 transition duration-200">
                                        <td class="p-3">{{ $post->id }}</td>
                                        <td class="p-3">{{ Str::limit($post->title, 15, '...') }}</td>
                                        <td class="p-3 text-center">{{ $post->created_at->format('d/m/Y') }}</td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('admin.manage.posts.detail', $post->id) }}"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition-all duration-300">
                                                🔍 ดูรายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
        
                    {{-- Pagination --}}
                    <div class="mt-auto p-4">
                        {{ $approvedPosts->appends(['rejected_page' => $rejectedPosts->currentPage()])->links() }}
                    </div>
                </div>
            @endif
        
            {{-- ตารางสำหรับ Rejected Posts --}}
            @if ($rejectedPosts->count() > 0)
                <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col h-full">
                    <h3 class="text-xl font-semibold p-4 bg-gray-100">❌ โพสต์ที่ถูกปฏิเสธ</h3>
                    
                    <div class="flex-grow overflow-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                    <th class="p-3 text-left">#</th>
                                    <th class="p-3 text-left">ชื่อโพสต์</th>
                                    <th class="p-3 text-center">วันที่สร้าง</th>
                                    <th class="p-3 text-center">รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rejectedPosts as $post)
                                    <tr class="border-b hover:bg-gray-100 transition duration-200">
                                        <td class="p-3">{{ $post->id }}</td>
                                        <td class="p-3">{{ Str::limit($post->title, 15, '...') }}</td>
                                        <td class="p-3 text-center">{{ $post->created_at->format('d/m/Y') }}</td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('admin.manage.posts.detail', $post->id) }}"
                                                class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition-all duration-300">
                                                🔍 ดูรายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
        
                    {{-- Pagination --}}
                    <div class="mt-auto p-4">
                        {{ $rejectedPosts->appends(['approved_page' => $approvedPosts->currentPage()])->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
