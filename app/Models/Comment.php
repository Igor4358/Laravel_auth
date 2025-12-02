<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content'
    ];

    protected $with = ['user']; // Автоматическая загрузка пользователя

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Получение комментариев для поста с кэшированием
     */
    public static function getForPostCached($postId)
    {
        return Cache::remember("comments.post.{$postId}", 1800, function () use ($postId) {
            return static::with('user')->where('post_id', $postId)->latest()->get();
        });
    }

    /**
     * Получение количества комментариев с кэшированием
     */
    public static function getCountCached()
    {
        return Cache::remember('comments.count', 3600, function () {
            return static::count();
        });
    }
}
