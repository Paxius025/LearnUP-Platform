<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class NotificationController extends Controller
{
    /**
     * ดึงรายการแจ้งเตือนของผู้ใช้ที่ล็อกอินอยู่
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $notifications = Notification::where('user_id', $user->id)
                                        ->where('is_read', false)
                                        ->latest()
                                        ->get();
        } else {
            // ✅ ดึงเฉพาะแจ้งเตือนของ User (ไม่ดึงของ Admin)
            $notifications = Notification::where('user_id', $user->id)
                                        ->where('is_read', false)
                                        ->latest()
                                        ->get();
        }

        return view('user.notifications', compact('notifications'));
    }
    /**
     * ดึงรายการแจ้งเตือนที่ยังไม่ได้อ่านของ User
     */
    public function getUnreadNotificationsForUser()
    {
        $unreadNotifications = Notification::where('user_id', Auth::id())
                                           ->where('is_read', false)
                                           ->latest()
                                           ->get();

        logAction('user_get_unread', "User " . Auth::user()->username . " fetched unread notifications.");

        return response()->json($unreadNotifications);
    }

    /**
     * ดึงรายการแจ้งเตือนที่ยังไม่ได้อ่านของ Admin
     */
    public function getUnreadNotificationsForAdmin()
    {
        $unreadNotifications = Notification::where('is_read', false)
                                           ->whereNull('user_id')
                                           ->latest()
                                           ->get();

        logAction('admin_get_unread', "Admin " . Auth::user()->username . " fetched unread notifications.");

        return response()->json($unreadNotifications);
    }

    /**
     * อัปเดตสถานะแจ้งเตือนของ User เป็น "อ่านแล้ว"
     */
    public function markNotificationAsReadForUser($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        logAction('user_mark_read', "User " . Auth::user()->username . " marked notification as read: " . $notification->message);

        return response()->json(['success' => true, 'message' => 'แจ้งเตือนถูกอ่านแล้ว']);
    }

    /**
     * อัปเดตสถานะแจ้งเตือนของ Admin เป็น "อ่านแล้ว"
     */
    public function markNotificationAsReadForAdmin($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('is_read', false)
                                    ->firstOrFail();

        $notification->is_admin_read = true;
        $notification->save();

        logAction('admin_mark_read', "Admin " . Auth::user()->username . " marked notification as read: " . $notification->message);

        return response()->json(['success' => true, 'message' => 'แจ้งเตือนถูกอ่านแล้ว']);
    }

    /**
     * อัปเดตสถานะแจ้งเตือนทั้งหมดของ User เป็น "อ่านแล้ว"
     */
    public function markAllNotificationsAsReadForUser()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->where('is_read', false)
                                     ->update(['is_read' => true]);

        logAction('user_mark_all_read', "User " . Auth::user()->username . " marked all notifications as read.");

        return response()->json(['success' => true, 'message' => "แจ้งเตือนทั้งหมดถูกอ่านแล้ว"]);
    }

    /**
     * อัปเดตสถานะแจ้งเตือนทั้งหมดของ Admin เป็น "อ่านแล้ว"
     */
    public function markAllNotificationsAsReadForAdmin()
    {
        $notifications = Notification::where('is_read', false)
                                     ->update(['is_read' => true]);

        logAction('admin_mark_all_read', "Admin " . Auth::user()->username . " marked all notifications as read.");

        return response()->json(['success' => true, 'message' => "แจ้งเตือนทั้งหมดถูกอ่านแล้ว"]);
    }
}