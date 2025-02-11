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

            @if (!$notifications->isEmpty() && Auth::user()->role === 'admin')
                <button onclick="markAllAsRead()" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                    ✔️ อ่านทั้งหมด
                </button>
            @endif
        </div>

        @if ($notifications->isEmpty())
            <p class="text-gray-500">ยังไม่มีการแจ้งเตือน</p>
        @else
            <!-- ใน Blade template -->
            <ul class="space-y-4" id="notification-list">
                @foreach ($notifications as $notification)
                    <li class="p-4 border rounded-lg flex justify-between items-center notification-item"
                        data-id="{{ $notification->id }}"
                        data-read="{{ $notification->is_user_read || $notification->is_admin_read ? 'true' : 'false' }}"
                        style="background-color: {{ $notification->is_user_read || $notification->is_admin_read ? '#e0e7ff' : '#c3daf8' }};">

                        <!-- แสดงชื่อผู้โพสต์ -->
                        <span class="text-gray-800 font-semibold">
                            {{ $notification->user->name }} ({{ ucfirst($notification->type) }})
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
                                    <button onclick="deleteNotification({{ $notification->id }})"
                                        class="bg-green-500 text-white px-3 py-1 rounded ">
                                        ✔️ อ่านแล้ว
                                    </button>
                                @endif
                            @else
                                <!-- User จะเห็นปุ่ม "ทำเครื่องหมายว่าอ่านแล้ว" -->
                                @if (!$notification->is_user_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded ">
                                        ❌ ยังไม่ถูกอ่าน
                                    </button>
                                @else
                                    <button onclick="deleteNotification({{ $notification->id }})"
                                        class="bg-green-500 text-white px-3 py-1 rounded ">
                                        ✔️ อ่านแล้ว
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

<script>
    // ฟังก์ชัน markAllAsRead สำหรับ Admin
    async function markAllAsRead() {
        try {
            let response = await fetch("{{ route('notifications.markAllNotificationsAsReadForAdmin') }}", {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Error: ' + response.statusText);
            }

            let data = await response.json();

            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.style.backgroundColor = '#f3f4f6'; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                    item.setAttribute('data-read', 'true'); // ตั้งค่าให้เป็นการอ่านแล้ว
                    item.querySelector('.bg-red-500').classList.replace('bg-red-500',
                    'bg-green-500'); // เปลี่ยนปุ่มเป็น "อ่านแล้ว"
                });
                updateNotificationCount();
            } else {
                console.error('ไม่สามารถทำการอ่านแจ้งเตือนทั้งหมด');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    // ฟังก์ชัน markAsRead สำหรับ User
    async function markAsRead(id) {
        try {
            let response = await fetch(`/notifications/${id}/read/user`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Error: ' + response.statusText);
            }

            let data = await response.json();

            if (data.success) {
                let notificationItem = document.querySelector(`[data-id='${id}']`);
                if (notificationItem) {
                    notificationItem.style.backgroundColor = '#f3f4f6'; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                    notificationItem.setAttribute('data-read', 'true'); // ตั้งค่าให้เป็นการอ่านแล้ว
                    let markReadButton = notificationItem.querySelector('.bg-red-500');
                    if (markReadButton) markReadButton.classList.replace('bg-red-500', 'bg-green-500');
                }
                updateNotificationCount();
            } else {
                console.error('ไม่สามารถทำการอ่านแจ้งเตือนนี้ได้');
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    // ฟังก์ชัน markAsRead สำหรับ Admin
    async function markAsReadAdmin(id) {
        try {
            let response = await fetch(`/notifications/${id}/read/admin`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Error: ' + response.statusText);
            }

            let data = await response.json();

            if (data.success) {
                let notificationItem = document.querySelector(`[data-id='${id}']`);
                if (notificationItem) {
                    notificationItem.style.backgroundColor = '#f3f4f6'; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                    notificationItem.setAttribute('data-read', 'true');
                    notificationItem.querySelector('.bg-red-500').classList.replace('bg-red-500', 'bg-green-500');
                }
                updateNotificationCount();
            } else {
                console.error('ไม่สามารถทำการอ่านแจ้งเตือนนี้ได้');
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async function updateNotificationCount() {
        try {
            let response = await fetch('/notifications/count');
            let data = await response.json();
            document.getElementById('notification-count').innerText = data.unreadCount || '';
        } catch (error) {
            console.error('Error updating notification count:', error);
        }
    }
</script>

</html>
