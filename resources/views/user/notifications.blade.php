<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือน</title>
    <!-- ใส่ CSS ที่จำเป็น (เช่น Tailwind CSS, custom styles) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    @include('components.navbar') <!-- นำ navbar มาใช้ที่นี่ -->

    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">🔔 การแจ้งเตือน</h2>

            <!-- ถ้าเป็น Admin ให้แสดงปุ่ม "อ่านทั้งหมด" ของ Admin -->
            @if (!$notifications->isEmpty() && Auth::user()->role === 'admin')
                <button onclick="markAllAsReadAdmin()"
                    class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                    ✔️ อ่านทั้งหมด
                </button>
            @endif

            <!-- ถ้าเป็น User ให้แสดงปุ่ม "อ่านทั้งหมด" ของ User -->
            @if ((!$notifications->isEmpty() && Auth::user()->role === 'user') || Auth::user()->role === 'writer')
                <button onclick="markAllAsReadUser()"
                    class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                    ✔️ อ่านทั้งหมด
                </button>
            @endif

        </div>

        @if ($notifications->isEmpty())
            <p class="text-gray-500">ยังไม่มีการแจ้งเตือน</p>
        @else
            <ul class="space-y-4" id="notification-list">
                @foreach ($notifications as $notification)
                    <li class="p-4 border rounded-lg flex justify-between items-center notification-item"
                        data-id="{{ $notification->id }}"
                        data-read="{{ $notification->is_user_read || $notification->is_admin_read ? 'true' : 'false' }}"
                        style="background-color: {{ $notification->is_user_read || $notification->is_admin_read ? '#e0e7ff' : '#c3daf8' }};">
                        <!-- แสดงชื่อผู้โพสต์ -->
                        <span class="text-gray-800 font-semibold">
                            {{ $notification->user->name }} ({{ ucfirst($notification->type) }})
                            @if ($notification->type === 'new_post')
                                <p>{{ $notification->message }}</p>
                            @elseif ($notification->type === 'approved_post')
                                <p>{{ $notification->message }}</p>
                            @elseif ($notification->type === 'rejected_post')
                                <p>{{ $notification->message }}</p>
                            @elseif ($notification->type === 'role_updated')
                                <p>{{ $notification->message }}</p>
                            @endif
                        </span>

                        <div class="space-x-2">
                            @if (Auth::user()->role === 'admin')
                                <!-- Admin จะเห็นปุ่ม "ทำเครื่องหมายว่าอ่านแล้ว" และ "ลบ" -->
                                @if (!$notification->is_admin_read)
                                    <button onclick="markAsReadAdmin({{ $notification->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">
                                        ❌ ยังไม่ถูกอ่าน
                                    </button>
                                @else
                                    <button class="bg-green-500 text-white px-3 py-1 rounded">
                                        ✔️ อ่านแล้ว
                                    </button>
                                @endif
                            @else
                                <!-- User จะเห็นปุ่ม "ทำเครื่องหมายว่าอ่านแล้ว" -->
                                @if (!$notification->is_user_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">❌ ยังไม่ถูกอ่าน</button>
                                @else
                                    <!-- ปุ่มสำหรับ User ที่จะอ่านทั้งหมด -->
                                    <button onclick="markAllAsReadUser()"
                                        class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                                        ✔️ อ่านทั้งหมด
                                    </button>
                                @endif
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</body>

<script src="{{ asset('js/notifications.js') }}"></script>

</html>
