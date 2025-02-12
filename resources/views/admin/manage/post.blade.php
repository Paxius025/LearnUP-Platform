@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">จัดการโพสต์</h2>

        <div class="grid grid-cols-4 gap-6 mt-6">
            <x-dashboard-stat-card title="Total Posts" count="{{ $totalPosts }}" color="green" icon="📌" />
            <x-dashboard-stat-card title="Approved Posts" count="{{ $approvedCount }}" color="blue" icon="✅" />
            <x-dashboard-stat-card title="Rejected Posts" count="{{ $rejectedCount }}" color="red" icon="❌" />
            <x-dashboard-stat-card title="Pending Approval" count="{{ $pendingCount }}" color="yellow" icon="⏳" />
        </div>

        @foreach (['approved' => $approvedPosts, 'rejected' => $rejectedPosts] as $status => $posts)
            @if ($posts->count() > 0)
                <h3 class="text-xl font-semibold mt-6 mb-2">
                    @if ($status == 'approved')
                        ✅ โพสต์ที่อนุมัติแล้ว
                    @else
                        ❌ โพสต์ที่ถูกปฏิเสธ
                    @endif
                </h3>

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">#</th>
                            <th class="border p-2">ชื่อโพสต์</th>
                            <th class="border p-2">สถานะ</th>
                            <th class="border p-2">สร้างเมื่อ</th>
                            <th class="border p-2">รายละเอียดโพสต์</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr class="border">
                                <td class="border p-2">{{ $post->id }}</td>
                                <td class="border p-2">{{ $post->title }}</td>
                                <td class="border p-2 text-{{ $status == 'approved' ? 'green-600' : 'red-600' }}">
                                    {{ $status == 'approved' ? 'อนุมัติแล้ว' : 'ถูกปฏิเสธ' }}
                                </td>
                                <td class="border p-2">{{ $post->created_at->format('d/m/Y') }}</td>
                                <td class="border p-2 text-center">
                                    <a href="{{ route('admin.manage.posts.detail', $post->id) }}"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition-all duration-300">
                                        🔍 ดูรายละเอียด
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>
@endsection
