@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">จัดการโพสต์</h2>

        <div class="grid grid-cols-4 gap-6 mt-6">
            <x-dashboard-stat-card title="Total Posts" count="{{ $totalPosts }}" color="green" icon="📌" />
            <x-dashboard-stat-card title="Approved Posts" count="{{ $approvedPosts }}" color="blue" icon="✅" />
            <x-dashboard-stat-card title="Pending Approval" count="{{ $pendingCount }}" color="yellow" icon="⏳" />
            <x-dashboard-stat-card title="Rejected Posts" count="{{ $rejectedPosts }}" color="red" icon="❌" />
        </div>

        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">#</th>
                    <th class="border p-2">ชื่อโพสต์</th>
                    <th class="border p-2">สถานะ</th>
                    <th class="border p-2">สร้างเมื่อ</th>
                    <th class="border p-2">ตัวเลือก</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingPosts as $post)
                    <tr class="border">
                        <td class="border p-2">{{ $post->id }}</td>
                        <td class="border p-2">{{ $post->title }}</td>
                        <td class="border p-2 text-yellow-600">รออนุมัติ</td>
                        <td class="border p-2">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="border p-2">
                            <a href="#" class="text-green-600">✅ อนุมัติ</a> |
                            <a href="#" class="text-red-600">❌ ปฏิเสธ</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
