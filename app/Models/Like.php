<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    
    protected $table = 'likes';

    protected $fillable = ['user_id', 'post_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function toggleLike($userId, $postId)
    {
        // หาไลค์ที่ตรงกับ user_id และ post_id
        $like = self::where('user_id', $userId)
                    ->where('post_id', $postId)
                    ->first();

        if ($like) {
            $like->delete();
            return false;  
        } else {
         
            self::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            return true;  
        }
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}