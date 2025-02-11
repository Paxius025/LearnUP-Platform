<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Log;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = Log::latest()->paginate(10);

        return view('admin.logs', compact('logs'));
    }
    public function stat(Request $request)
    {
        // ค่า default ของ range คือ 7 วัน
        $range = $request->input('range', 7);

        // ดึงสถิติหลัก
        $totalPosts = Post::count();
        $totalUsers = User::count();
        $totalWriters = User::where('role', 'writer')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalLogs = Log::count();

        // ✅ ปรับปรุงการจัดกลุ่ม Log
        $logStats = Log::selectRaw("
            CASE 
                WHEN action IN ('approve_post', 'create_post', 'update_post', 'delete_post', 'reject_post') THEN 'post_actions'
                WHEN action IN ('create_comment', 'update_comment', 'delete_comment') THEN 'comment_actions'
                WHEN action IN ('login', 'logout', 'register') THEN 'auth_actions'
                WHEN action IN ('upload_image') THEN 'upload_actions'
                WHEN action IN ('notify_admin', 'read_notification', 'delete_notification') THEN 'notification_actions'
                WHEN action IN ('toggle_like') THEN 'like_actions'
                WHEN action IN ('admin_mark_read', 'admin_mark_all_read') THEN 'admin_read_notifications'
                WHEN action IN ('user_mark_read', 'user_mark_all_read') THEN 'user_read_notifications'
                ELSE action
            END AS grouped_action,
            COUNT(*) as count
        ")
        ->groupBy('grouped_action')
        ->orderByDesc('count')
        ->get();

        // ✅ ปรับปรุงการดึงข้อมูลแนวโน้ม Log ตามช่วงเวลา
        $logTrends = Log::selectRaw("
            DATE(created_at) as date,
            CASE 
                WHEN action IN ('approve_post', 'create_post', 'update_post', 'delete_post', 'reject_post') THEN 'post_actions'
                WHEN action IN ('create_comment', 'update_comment', 'delete_comment') THEN 'comment_actions'
                WHEN action IN ('login', 'logout', 'register') THEN 'auth_actions'
                WHEN action IN ('upload_image') THEN 'upload_actions'
                WHEN action IN ('notify_admin', 'read_notification', 'delete_notification') THEN 'notification_actions'
                WHEN action IN ('toggle_like') THEN 'like_actions'
                WHEN action IN ('admin_mark_read', 'admin_mark_all_read') THEN 'admin_read_notifications'
                WHEN action IN ('user_mark_read', 'user_mark_all_read') THEN 'user_read_notifications'
                ELSE action
            END AS grouped_action,
            COUNT(*) as count
        ")
        ->where('created_at', '>=', Carbon::now()->subDays($range))
        ->groupByRaw('DATE(created_at), grouped_action')
        ->orderByRaw('DATE(created_at) ASC')
        ->get();

        return view('admin.stat', compact(
            'logStats',
            'logTrends',
            'totalPosts',
            'totalUsers',
            'totalWriters',
            'totalAdmins',
            'totalLogs'
        ));
    }
}