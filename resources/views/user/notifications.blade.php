@extends('layouts.app')

@section('content')
    <div class="max-w-4xl w-full mx-auto bg-white border border-gray-200 rounded-lg p-6 mt-[100px]">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">🔔 Notifications</h2>

            @if (!$notifications->isEmpty() && Auth::user()->role === 'admin')
                <button onclick="markAllAsReadAdmin()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    ✔️  Mark All as Read
                </button>
            @endif

            @if ((!$notifications->isEmpty() && Auth::user()->role === 'user') || Auth::user()->role === 'writer')
                <button onclick="markAllAsReadUser()" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                    ✔️  Mark All as Read
                </button>
            @endif
        </div>

        @if ($notifications->isEmpty())
            <p class="text-gray-500">No recent alerts</p>
        @else
            <ul class="space-y-4" id="notification-list">
                @foreach ($notifications as $notification)
                    <li class="p-4 border rounded-lg flex justify-between items-center notification-item"
                        data-id="{{ $notification->id }}"
                        data-read="{{ $notification->is_read  ? 'true' : 'false' }}"
                        style="background-color: {{ $notification->is_read  ? '#e0e7ff' : '#c3daf8' }};">

                        <span class="text-gray-800 font-semibold">
                            {{ $notification->user->name }} ({{ ucfirst($notification->type) }})
                            <p>{{ $notification->message }}</p>
                        </span>

                        <div class="space-x-2">
                            @if (Auth::user()->role === 'admin')
                                @if (!$notification->is_read)
                                    <button onclick="markAsReadAdmin({{ $notification->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">
                                        ❌ Unread
                                    </button>
                                @else
                                    <button class="bg-green-500 text-white px-3 py-1 rounded">
                                        ✔️ Read
                                    </button>
                                @endif
                            @else
                                @if (!$notification->is_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded">
                                        ❌ Unread
                                    </button>
                                @else
                                    <button onclick="markAllAsReadUser()"
                                        class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
                                        ✔️ Read
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
                let response = await fetch(
                    "{{ route('notifications.markAllNotificationsAsReadForAdmin') }}", {
                        method: "PATCH",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                    }
                );
    
                if (!response.ok) {
                    throw new Error("Error: " + response.statusText);
                }
    
                let data = await response.json();
    
                if (data.success) {
                    // Use querySelectorAll to change the read status of all notifications
                    document.querySelectorAll(".notification-item").forEach((item) => {
                        item.style.backgroundColor = "#f3f4f6"; // Change background color to read
                        item.setAttribute("data-read", "true"); // Set read attribute to true
    
                        // Select button inside notification-item
                        let markReadButton = item.querySelector("button.bg-red-500");
                        if (markReadButton) {
                            markReadButton.classList.replace(
                                "bg-red-500",
                                "bg-green-500"
                            ); // Change button color
                            markReadButton.textContent = "✔️ Read"; // Change button text
                        }
                    });
                    updateNotificationCount();
                } else {
                    console.error("Unable to mark all notifications as read");
                }
            } catch (error) {
                console.error(
                    "Error marking all notifications as read for Admin:",
                    error
                );
            }
        }
    
        // markAsRead function for User
        async function markAsRead(id) {
            try {
                let response = await fetch(`/notifications/${id}/read/user`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                });
    
                if (!response.ok) {
                    throw new Error("Error: " + response.statusText);
                }
    
                let data = await response.json();
    
                if (data.success) {
                    let notificationItem = document.querySelector(`[data-id='${id}']`);
                    if (notificationItem) {
                        notificationItem.style.backgroundColor = "#f3f4f6"; // Change background color to read
                        notificationItem.setAttribute("data-read", "true"); // Set read attribute to true
                        let markReadButton =
                            notificationItem.querySelector(".bg-red-500");
                        if (markReadButton)
                            markReadButton.classList.replace(
                                "bg-red-500",
                                "bg-green-500"
                            );
                        markReadButton.textContent = "✔️ Read";  
                    }
                    updateNotificationCount();
                } else {
                    console.error("Unable to mark all notifications as read");
                }
            } catch (error) {
                console.error("Error marking notification as read:", error);
            }
        }
    
        async function markAsReadAdmin(id) {
            try {
                let response = await fetch(`/notifications/${id}/read/admin`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                });
    
                if (!response.ok) {
                    throw new Error("Error: " + response.statusText);
                }
    
                let data = await response.json();
    
                if (data.success) {
                    let notificationItem = document.querySelector(`[data-id='${id}']`);
                    if (notificationItem) {
                        notificationItem.style.backgroundColor = "#f3f4f6"; // Change background color to read
                        notificationItem.setAttribute("data-read", "true");
    
                        // Select button inside notificationItem
                        let markReadButton = notificationItem.querySelector("button");
                        if (markReadButton) {
                            markReadButton.classList.replace(
                                "bg-red-500",
                                "bg-green-500"
                            ); // Change button color
                            markReadButton.textContent = "✔️ Read"; // Change button text
                        }
                    }
                    updateNotificationCount();
                } else {
                    console.error("Unable to mark this notification as read");
                }
            } catch (error) {
                console.error("Error marking notification as read for Admin:", error);
            }
        }
    
        // markAllAsRead function for User
        async function markAllAsReadUser() {
            try {
                let response = await fetch(
                    "{{ route('notifications.markAllNotificationsAsReadForUser') }}", {
                        method: "PATCH",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                    }
                );
    
                if (!response.ok) {
                    throw new Error("Error: " + response.statusText);
                }
    
                let data = await response.json();
    
                if (data.success) {
                    // Change the status of all notifications to "read"
                    document.querySelectorAll(".notification-item").forEach((item) => {
                        item.style.backgroundColor = "#f3f4f6"; // Change background color to read
                        item.setAttribute("data-read", "true"); // Set read attribute to true
                        let markReadButton = item.querySelector("button");
                        if (markReadButton) {
                            markReadButton.classList.replace(
                                "bg-red-500",
                                "bg-green-500"
                            ); // Change button color
                            markReadButton.textContent = "✔️ Read"; // Change button text
                        }
                    });
                    updateNotificationCount();
                } else {
                    console.error("Unable to mark all notifications as read");
                }
            } catch (error) {
                console.error("Error marking all notifications as read:", error);
            }
        }
    
        async function updateNotificationCount() {
            try {
                let response = await fetch("/notifications/count");
                let data = await response.json();
                document.getElementById("notification-count").innerText =
                    data.unreadCount || "";
            } catch (error) {
                console.error("Error updating notification count:", error);
            }
        }
    </script>
    
@endsection