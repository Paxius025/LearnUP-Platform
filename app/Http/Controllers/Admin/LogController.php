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
        // ค่า default ของ range คือ 7
        $range = $request->input('range', 7); 

        $totalPosts = Post::count();
        $totalUsers = User::count();
        $totalWriters = User::where('role', 'writer')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalLogs = Log::count();

        // ดึงข้อมูล Log ตามประเภท (action) และนับจำนวนครั้งที่เกิดขึ้น
        $logStats = Log::selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->get();

        // ดึงข้อมูล Log ในช่วงเวลาที่เลือก
        $logTrends = Log::selectRaw("DATE(created_at) as date, action, COUNT(*) as count")
            ->where('created_at', '>=', Carbon::now()->subDays($range))
            ->groupByRaw('DATE(created_at), action')
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