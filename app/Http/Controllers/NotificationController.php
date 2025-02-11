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
        // ตรวจสอบว่าเป็น Admin หรือเจ้าของโพสต์
        if (Auth::user()->role === 'admin') {
            // ถ้าเป็น Admin, ให้ดูการแจ้งเตือนทั้งหมด
            $notifications = Notification::latest()->get();
        } else {
            // ถ้าเป็น User (เจ้าของโพสต์), ให้ดึงเฉพาะการแจ้งเตือนของโพสต์ที่ตัวเองสร้าง
            $notifications = Notification::where('user_id', Auth::id())
                                        ->latest()
                                        ->get();
        }

        return view('user.notifications', compact('notifications'));
    }

    /**
     * อัปเดตสถานะแจ้งเตือนเป็น "อ่านแล้ว"
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', Auth::id())  // ตรวจสอบว่าผู้ใช้ที่ล็อกอินต้องเป็นเจ้าของการแจ้งเตือน
                                    ->firstOrFail();

        // อัปเดตสถานะเป็น "อ่านแล้ว"
        $notification->is_read = true;
        $notification->save();

        // บันทึก Log
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
        $deletedCount = Notification::where('user_id', Auth::id())
                                    ->where('is_read', true)
                                    ->delete();

        logAction('delete_all_read_notifications', "User " . Auth::user()->username . " ลบแจ้งเตือนที่อ่านแล้วทั้งหมด");

        return response()->json([
            'success' => true,
            'message' => "ลบแจ้งเตือนที่อ่านแล้วสำเร็จ ({$deletedCount} รายการ)"
        ]);
    }

    public function getNotificationCount()
    {
        // นับจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน
        $unreadCount = Notification::where('user_id', Auth::id())
                                ->where('is_read', false)
                                ->count();

        return response()->json(['unreadCount' => $unreadCount]);
    }

}