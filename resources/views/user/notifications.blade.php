@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">🔔 การแจ้งเตือน</h2>

    @if ($notifications->isEmpty())
        <p class="text-gray-500">ไม่มีการแจ้งเตือน</p>
    @else
        <ul class="space-y-4" id="notification-list">
            @foreach ($notifications as $notification)
                <li class="p-4 border rounded-lg flex justify-between items-center notification-item"
                    data-id="{{ $notification->id }}"
                    style="background-color: {{ $notification->is_read ? '#f3f4f6' : '#c3daf8' }};">
                    
                    <span class="text-gray-800">
                        {{ $notification->message }}
                    </span>

                    <div class="space-x-2">
                        @if (!$notification->is_read)
                            <button id="mark-read-{{ $notification->id }}" onclick="markAsRead({{ $notification->id }})"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                ✔️ อ่านแล้ว
                            </button>
                        @endif

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

<script>
   async function markAsRead(id) {
        try {
            let response = await fetch(`/notifications/${id}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            if (response.ok) {
                let notificationItem = document.querySelector(`[data-id='${id}']`);
                if (notificationItem) {
                    // เปลี่ยนพื้นหลังให้เป็นแจ้งเตือนที่อ่านแล้ว
                    notificationItem.style.backgroundColor = '#f3f4f6';
                    
                    // ซ่อนปุ่ม "✔️ อ่านแล้ว"
                    let markReadButton = document.getElementById(`mark-read-${id}`);
                    if (markReadButton) {
                        markReadButton.remove();
                    }
                }
                updateNotificationCount();
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

            if (response.ok) {
                document.querySelector(`[data-id='${id}']`).remove();
                updateNotificationCount();
            }
        } catch (error) {
            console.error('Error deleting notification:', error);
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
@endsection
