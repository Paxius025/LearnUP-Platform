@extends('layouts.app')

@section('title', 'การแจ้งเตือน')
@section('content')
    <div class="max-w-4xl w-full mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">🔔 การแจ้งเตือน</h2>

            @if (!$notifications->isEmpty() && Auth::user()->role === 'admin')
                <button onclick="markAllAsReadAdmin()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    ✔️ อ่านทั้งหมด
                </button>
            @endif

            @if ((!$notifications->isEmpty() && Auth::user()->role === 'user') || Auth::user()->role === 'writer')
                <button onclick="markAllAsReadUser()" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
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

                        <span class="text-gray-800 font-semibold">
                            {{ $notification->user->name }} ({{ ucfirst($notification->type) }})
                            <p>{{ $notification->message }}</p>
                        </span>

                        <div class="space-x-2">
                            @if (Auth::user()->role === 'admin')
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
                                @if (!$notification->is_user_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">
                                        ❌ ยังไม่ถูกอ่าน
                                    </button>
                                @else
                                    <button onclick="markAllAsReadUser()"
                                        class="bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600">
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

    <script>
        async function markAllAsReadAdmin() {
            try {
                let response = await fetch("{{ route('notifications.markAllNotificationsAsReadForAdmin') }}", {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                });

                if (!response.ok) throw new Error("Error: " + response.statusText);

                let data = await response.json();
                if (data.success) {
                    document.querySelectorAll(".notification-item").forEach((item) => {
                        item.style.backgroundColor = "#f3f4f6";
                        item.setAttribute("data-read", "true");
                        let markReadButton = item.querySelector("button.bg-red-500");
                        if (markReadButton) {
                            markReadButton.classList.replace("bg-red-500", "bg-green-500");
                            markReadButton.textContent = "✔️ อ่านแล้ว";
                        }
                    });
                    updateNotificationCount();
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        async function markAsRead(id) {
            try {
                let response = await fetch(`/notifications/${id}/read/user`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                });

                if (!response.ok) throw new Error("Error: " + response.statusText);

                let data = await response.json();
                if (data.success) {
                    let notificationItem = document.querySelector(`[data-id='${id}']`);
                    if (notificationItem) {
                        notificationItem.style.backgroundColor = "#f3f4f6";
                        notificationItem.setAttribute("data-read", "true");
                        let markReadButton = notificationItem.querySelector(".bg-red-500");
                        if (markReadButton) {
                            markReadButton.classList.replace("bg-red-500", "bg-green-500");
                            markReadButton.textContent = "✔️ อ่านแล้ว";
                        }
                    }
                    updateNotificationCount();
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        async function updateNotificationCount() {
            try {
                let response = await fetch("/notifications/count");
                let data = await response.json();
                document.getElementById("notification-count").innerText = data.unreadCount || "";
            } catch (error) {
                console.error("Error:", error);
            }
        }
    </script>
@endsection
