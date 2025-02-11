// ฟังก์ชัน markAllAsRead สำหรับ Admin
async function markAllAsReadAdmin() {
    try {
        let response = await fetch(
            "{{ route('notifications.markAllNotificationsAsReadForAdmin') }}",
            {
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
            // ใช้ querySelectorAll เพื่อเปลี่ยนสถานะการอ่านของทุกการแจ้งเตือน
            document.querySelectorAll(".notification-item").forEach((item) => {
                item.style.backgroundColor = "#f3f4f6"; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                item.setAttribute("data-read", "true"); // ตั้งค่าให้เป็นการอ่านแล้ว

                // เลือกปุ่มภายใน notification-item
                let markReadButton = item.querySelector("button.bg-red-500");
                if (markReadButton) {
                    markReadButton.classList.replace(
                        "bg-red-500",
                        "bg-green-500"
                    ); // เปลี่ยนสีของปุ่ม
                    markReadButton.textContent = "✔️ อ่านแล้ว"; // เปลี่ยนข้อความในปุ่ม
                }
            });
            updateNotificationCount();
        } else {
            console.error("ไม่สามารถทำการอ่านแจ้งเตือนทั้งหมด");
        }
    } catch (error) {
        console.error(
            "Error marking all notifications as read for Admin:",
            error
        );
    }
}

// ฟังก์ชัน markAsRead สำหรับ User
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
                notificationItem.style.backgroundColor = "#f3f4f6"; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                notificationItem.setAttribute("data-read", "true"); // ตั้งค่าให้เป็นการอ่านแล้ว
                let markReadButton =
                    notificationItem.querySelector(".bg-red-500");
                if (markReadButton)
                    markReadButton.classList.replace(
                        "bg-red-500",
                        "bg-green-500"
                    );
                markReadButton.textContent = "✔️ อ่านแล้ว"; // เปลี่ยนข้อความ
            }
            updateNotificationCount();
        } else {
            console.error("ไม่สามารถทำการอ่านแจ้งเตือนนี้ได้");
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
                notificationItem.style.backgroundColor = "#f3f4f6"; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                notificationItem.setAttribute("data-read", "true");

                // เลือกปุ่มที่เป็นลูกของ notificationItem
                let markReadButton = notificationItem.querySelector("button");
                if (markReadButton) {
                    markReadButton.classList.replace(
                        "bg-red-500",
                        "bg-green-500"
                    ); // เปลี่ยนสีของปุ่ม
                    markReadButton.textContent = "✔️ อ่านแล้ว"; // เปลี่ยนข้อความในปุ่ม
                }
            }
            updateNotificationCount();
        } else {
            console.error("ไม่สามารถทำการอ่านแจ้งเตือนนี้ได้");
        }
    } catch (error) {
        console.error("Error marking notification as read for Admin:", error);
    }
}

// ฟังก์ชัน markAllAsRead สำหรับ User
async function markAllAsReadUser() {
    try {
        let response = await fetch(
            "{{ route('notifications.markAllNotificationsAsReadForUser') }}",
            {
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
            // เปลี่ยนสถานะของการแจ้งเตือนทั้งหมดให้เป็น "อ่านแล้ว"
            document.querySelectorAll(".notification-item").forEach((item) => {
                item.style.backgroundColor = "#f3f4f6"; // เปลี่ยนสีพื้นหลังเป็นสีที่อ่านแล้ว
                item.setAttribute("data-read", "true"); // ตั้งค่าให้เป็นการอ่านแล้ว
                let markReadButton = item.querySelector("button");
                if (markReadButton) {
                    markReadButton.classList.replace(
                        "bg-red-500",
                        "bg-green-500"
                    ); // เปลี่ยนสีปุ่ม
                    markReadButton.textContent = "✔️ อ่านแล้ว"; // เปลี่ยนข้อความ
                }
            });
            updateNotificationCount();
        } else {
            console.error("ไม่สามารถทำการอ่านแจ้งเตือนทั้งหมด");
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
