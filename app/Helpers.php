<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logAction')) {
    function logAction($action, $description)
    {
        $userId = Auth::id() ?? null;

        // บันทึก log ลงในฐานข้อมูล
        Log::create([
            'user_id' => $userId, 
            'action' => $action,
            'description' => $description,
        ]);
    }
}