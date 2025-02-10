<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class NotificationController extends Controller
{
    /**
     * ดึงรายการแจ้งเตือนของผู้ใช้ที่ล็อกอินอยู่
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->latest()
                                     ->get();

        return view('user.notifications', compact('notifications'));
    }

    /**
     * อัปเดตสถานะแจ้งเตือนเป็น "อ่านแล้ว"
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

        $notification->markAsRead();

        logAction('read_notification', "User " . Auth::user()->username . " อ่านแจ้งเตือน: " . $notification->message);

        return response()->json(['success' => true, 'message' => 'แจ้งเตือนถูกอ่านแล้ว']);
    }

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

        $message = $notification->message;
        $notification->delete();

        logAction('delete_notification', "User " . Auth::user()->username . " ลบแจ้งเตือน: " . $message);

        return response()->json(['success' => true, 'message' => 'ลบแจ้งเตือนสำเร็จ']);
    }

    public function deleteReadNotifications()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', true)
                    ->delete();

        logAction('delete_all_read_notifications', "User " . Auth::user()->username . " ลบแจ้งเตือนที่อ่านแล้วทั้งหมด");

        return response()->json(['success' => true, 'message' => 'ลบแจ้งเตือนที่อ่านแล้วทั้งหมดสำเร็จ']);
    }

}