<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logAction')) {
    function logAction($action, $description)
    {
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $userId = null;
        }

        // บันทึก log ลงในฐานข้อมูล
        Log::create([
            'user_id' => $userId, 
            'action' => $action,
            'description' => $description,
        ]);
    }
}