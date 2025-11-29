<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Родительская категория
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Дочерние категории
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    // Посты в этой категории
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Рекурсивное получение всех дочерних категорий
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Рекурсивное получение всех постов в категории и подкатегориях
    public function getAllPosts()
    {
        $posts = $this->posts;

        foreach ($this->children as $child) {
            $posts = $posts->merge($child->getAllPosts());
        }

        return $posts;
    }

    // Получение полного пути категории
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

    // Получение дерева категорий
    public static function getTree()
    {
        return static::with('allChildren')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }
}
