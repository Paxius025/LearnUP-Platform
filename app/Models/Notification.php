<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
    ];

    /**
     * ความสัมพันธ์กับ User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope สำหรับดึงเฉพาะแจ้งเตือนที่ยังไม่ได้อ่าน
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark แจ้งเตือนเป็นอ่านแล้ว
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}