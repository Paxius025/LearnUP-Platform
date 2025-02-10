<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

function logAction($action, $description)
{
    Log::create([
        'user_id' => Auth::check() ? Auth::id() : null, 
        'action' => $action,
        'description' => $description,
    ]);
}