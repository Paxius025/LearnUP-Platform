<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

function logAction($action, $description)
{
    Log::create([
        'user_id' => Auth::id(),
        'action' => $action,
        'description' => $description,
    ]);
}