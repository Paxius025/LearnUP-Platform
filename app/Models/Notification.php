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
        'is_user_read',
        'is_admin_read',
    ];

    /**
     * ความสัมพันธ์กับ User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope สำหรับดึงเฉพาะแจ้งเตือนที่ยังไม่ได้อ่าน (สำหรับ User)
     */
    public function scopeUnreadUser($query)
    {
        return $query->where('user_id', auth()->id())->where('is_user_read', false);
    }

    /**
     * Scope สำหรับดึงเฉพาะแจ้งเตือนที่ยังไม่ได้อ่าน (สำหรับ Admin)
     */
    public function scopeUnreadAdmin($query)
    {
        return $query->where('is_admin_read', false);
    }

    /**
     * Mark แจ้งเตือนของ User เป็นอ่านแล้ว
     */
    public function markAsReadForUser()
    {
        $this->update(['is_user_read' => true]);
    }

    /**
     * Mark แจ้งเตือนของ Admin เป็นอ่านแล้ว
     */
    public function markAsReadForAdmin()
    {
        $this->update(['is_admin_read' => true]);
    }

    /**
     * Mark แจ้งเตือนทั้งหมดของ User เป็นอ่านแล้ว
     */
    public function markAllAsReadForUser()
    {
        $this->where('user_id', auth()->id())
             ->where('is_user_read', false)
             ->update(['is_user_read' => true]);
    }

    /**
     * Mark แจ้งเตือนทั้งหมดของ Admin เป็นอ่านแล้ว
     */
    public function markAllAsReadForAdmin()
    {
        $this->where('is_admin_read', false)
             ->update(['is_admin_read' => true]);
    }
}