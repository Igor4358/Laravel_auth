<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearAppCache extends Command
{
    protected $signature = 'cache:app-clear';
    protected $description = 'Clear application specific cache';

    public function handle(): void
    {
        Cache::forget('posts.all');
        Cache::forget('posts.latest');
        Cache::forget('posts.count');
        Cache::forget('categories.tree');
        Cache::forget('categories.all');

        // Очищаем кэш комментариев
        $posts = \App\Models\Post::pluck('id');
        foreach ($posts as $postId) {
            Cache::forget("comments.post.{$postId}");
        }

        // Очищаем кэш категорий
        $categories = \App\Models\Category::pluck('id');
        foreach ($categories as $categoryId) {
            Cache::forget("posts.category.{$categoryId}");
            Cache::forget("category.{$categoryId}.posts_count");
        }

        Log::info('Application cache cleared manually');
        $this->info('Application cache cleared successfully!');
    }
}
