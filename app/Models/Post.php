<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'pdf_file',
        'status',
    ];

    protected $casts = [
        'image' => 'array',
    ];
    /**
     * ผู้ใช้ที่เป็นเจ้าของโพสต์
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
     
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}