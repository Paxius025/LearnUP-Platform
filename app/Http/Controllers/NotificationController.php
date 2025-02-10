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

        // ✅ เก็บ Log การอ่านแจ้งเตือน
        logAction('read_notification', "User " . Auth::user()->username . " อ่านแจ้งเตือน: " . $notification->message);

        return redirect()->back()->with('success', 'แจ้งเตือนถูกอ่านแล้ว');
    }

    /**
     * ลบแจ้งเตือน
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

        $message = $notification->message;
        $notification->delete();

        // ✅ เก็บ Log การลบแจ้งเตือน
        logAction('delete_notification', "User " . Auth::user()->username . " ลบแจ้งเตือน: " . $message);

        return redirect()->back()->with('success', 'ลบแจ้งเตือนสำเร็จ');
    }
}