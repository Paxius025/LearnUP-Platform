<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือน</title>
    <!-- ใส่ CSS ที่จำเป็น (เช่น Tailwind CSS, custom styles) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- สามารถใส่ CSS อื่น ๆ ได้ที่นี่ -->
</head>

<body class="bg-gray-100">
    @include('components.navbar') <!-- นำ navbar มาใช้ที่นี่ -->

    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">🔔 การแจ้งเตือน</h2>

            @if (!$notifications->isEmpty())
                <button onclick="deleteAllReadNotifications()"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    🗑️ ลบทั้งหมดที่อ่านแล้ว
                </button>
            @endif
        </div>

        @if ($notifications->isEmpty())
            <p class="text-gray-500">ไม่มีการแจ้งเตือน</p>
        @else
            <ul class="space-y-4" id="notification-list">
                @foreach ($notifications as $notification)
                    <li class="p-4 border rounded-lg flex justify-between items-center notification-item"
                        data-id="{{ $notification->id }}" data-read="{{ $notification->is_read ? 'true' : 'false' }}"
                        style="background-color: {{ $notification->is_read ? '#f3f4f6' : '#c3daf8' }};">

                        <!-- แสดงชื่อผู้โพสต์ -->
                        <span class="text-gray-800 font-semibold">
                            {{ $notification->user->name }} ({{ ucfirst($notification->type) }})
                        </span>

                        <div class="space-x-2">
                            @if (!$notification->is_read)
                                <!-- ปุ่ม อ่านแล้ว -->
                                <button id="mark-read-{{ $notification->id }}"
                                    onclick="markAsRead({{ $notification->id }})"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    ✔️ อ่านแล้ว
                                </button>
                            @endif

                            <!-- ปุ่ม ลบ -->
                            <button onclick="deleteNotification({{ $notification->id }})"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                ❌ ลบ
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
<script>
    // ฟังก์ชัน markAsRead ที่อยู่ด้านบนสุด
    async function markAsRead(id) {
        try {
            let response = await fetch(`/notifications/${id}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            // ตรวจสอบว่า response สำเร็จหรือไม่
            if (!response.ok) {
                throw new Error('Error: ' + response.statusText);
            }

            // ตรวจสอบว่าข้อมูลที่ได้รับเป็น JSON หรือไม่
            let data = await response.json();

            if (data.success) {
                let notificationItem = document.querySelector(`[data-id='${id}']`);
                if (notificationItem) {
                    notificationItem.style.backgroundColor = '#f3f4f6';
                    let markReadButton = document.getElementById(`mark-read-${id}`);
                    if (markReadButton) markReadButton.remove();
                }
                updateNotificationCount();
            } else {
                console.error('ไม่สามารถทำการอ่านแจ้งเตือนนี้ได้');
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }


    async function deleteNotification(id) {
        try {
            let response = await fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            let data = await response.json();
            if (data.success) {
                document.querySelector(`[data-id='${id}']`).remove();
                updateNotificationCount();
            }
        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    }

    async function deleteAllReadNotifications() {
        if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการลบแจ้งเตือนที่อ่านแล้วทั้งหมด?')) return;

        try {
            let response = await fetch("{{ route('notifications.deleteRead') }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            let data = await response.json();
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    if (item.dataset.read === "true") {
                        item.remove();
                    }
                });
                alert(data.message);
                updateNotificationCount();
            }
        } catch (error) {
            console.error('Error deleting read notifications:', error);
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
