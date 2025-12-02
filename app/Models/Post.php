<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'image'
    ];

    protected $with = ['user', 'category']; // Автоматическая загрузка связей

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Получение всех постов с кэшированием
     */
    public static function getAllCached()
    {
        return Cache::remember('posts.all', 1800, function () { // 30 минут
            return static::with(['user', 'category', 'comments.user'])->latest()->get();
        });
    }

    /**
     * Получение последних постов с кэшированием
     */
    public static function getLatestCached($limit = 10)
    {
        return Cache::remember("posts.latest.{$limit}", 900, function () use ($limit) { // 15 минут
            return static::with(['user', 'category'])->latest()->limit($limit)->get();
        });
    }

    /**
     * Получение количества постов с кэшированием
     */
    public static function getCountCached()
    {
        return Cache::remember('posts.count', 3600, function () { // 1 час
            return static::count();
        });
    }
    public static function getPaginatedCached($perPage = 10)
    {
        $page = request()->get('page', 1);

        return Cache::remember("posts.paginated.page{$page}.perpage{$perPage}", 1800, function () use ($perPage) {
            return static::with(['user', 'category', 'comments.user'])->latest()->paginate($perPage);
        });
    }
    public static function getByCategoryCached($categoryId)
    {
        return Cache::remember("posts.category.{$categoryId}", 1800, function () use ($categoryId) {
            return static::with(['user', 'category', 'comments.user'])
                ->where('category_id', $categoryId)
                ->latest()
                ->get();
        });
    }
}
