<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content',
    ];

    /**
     * ความสัมพันธ์กับ User (ผู้เขียนคอมเมนต์)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ความสัมพันธ์กับ Post (โพสต์ที่ถูกคอมเมนต์)
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * ความสัมพันธ์กับคอมเมนต์หลัก (กรณีเป็น Reply)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * ความสัมพันธ์กับคอมเมนต์ลูก (Reply)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}