<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

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

        $notification->delete();

        return redirect()->back()->with('success', 'ลบแจ้งเตือนสำเร็จ');
    }
}