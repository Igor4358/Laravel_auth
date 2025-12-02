<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Очистка кэша при любых изменениях постов
     */
    private function clearCache(): void
    {
        // Очищаем старый кэш
        Cache::forget('posts.all');
        Cache::forget('posts.latest');
        Cache::forget('posts.count');

        // Очищаем пагинированный кэш (все страницы)
        Cache::flush(); // Или более точечно, но flush проще

        // Очищаем кэш категорий
        Cache::forget('categories.tree');
        Cache::forget('categories.all');

        Log::info('Post cache cleared');
    }

    /**
     * Обработка события создания поста
     */
    public function created(Post $post): void
    {
        Log::info("Post created: {$post->id} - {$post->title}");
        $this->clearCache();
    }

    /**
     * Обработка события обновления поста
     */
    public function updated(Post $post): void
    {
        Log::info("Post updated: {$post->id} - {$post->title}");
        $this->clearCache();
    }

    /**
     * Обработка события удаления поста
     */
    public function deleted(Post $post): void
    {
        Log::info("Post deleted: {$post->id} - {$post->title}");
        $this->clearCache();

        // Удаляем изображение поста
        if ($post->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
        }
    }

    /**
     * Обработка события восстановления поста
     */
    public function restored(Post $post): void
    {
        Log::info("Post restored: {$post->id} - {$post->title}");
        $this->clearCache();
    }

    /**
     * Обработка события полного удаления поста
     */
    public function forceDeleted(Post $post): void
    {
        Log::info("Post force deleted: {$post->id} - {$post->title}");
        $this->clearCache();
    }
}
