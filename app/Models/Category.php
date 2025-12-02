<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'order'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function getAllPosts()
    {
        $posts = $this->posts;

        foreach ($this->children as $child) {
            $posts = $posts->merge($child->getAllPosts());
        }

        return $posts;
    }

    public function getFullPath()
    {
        $path = collect([$this]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent);
            $parent = $parent->parent;
        }

        return $path;
    }

    /**
     * Получение дерева категорий с кэшированием
     */
    public static function getTree()
    {
        return Cache::remember('categories.tree', 3600, function () { // Кэш на 1 час
            return static::with('allChildren')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Получение всех категорий с кэшированием
     */
    public static function getAllCached()
    {
        return Cache::remember('categories.all', 3600, function () {
            return static::with('parent', 'children')->get();
        });
    }

    /**
     * Получение количества постов в категории с кэшированием
     */
    public function getPostsCountAttribute()
    {
        return Cache::remember("category.{$this->id}.posts_count", 1800, function () { // 30 минут
            return $this->getAllPosts()->count();
        });
    }
}
