<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoryObserver
{
    /**
     * Очистка кэша категорий
     */
    private function clearCache(): void
    {
        Cache::forget('categories.tree');
        Cache::forget('categories.all');
        Cache::forget('posts.all'); // Посты тоже могут зависеть от категорий

        Log::info('Category cache cleared');
    }

    public function created(Category $category): void
    {
        Log::info("Category created: {$category->id} - {$category->name}");
        $this->clearCache();
    }

    public function updated(Category $category): void
    {
        Log::info("Category updated: {$category->id} - {$category->name}");
        $this->clearCache();
    }

    public function deleted(Category $category): void
    {
        Log::info("Category deleted: {$category->id} - {$category->name}");
        $this->clearCache();
    }

    public function restored(Category $category): void
    {
        Log::info("Category restored: {$category->id} - {$category->name}");
        $this->clearCache();
    }

    public function forceDeleted(Category $category): void
    {
        Log::info("Category force deleted: {$category->id} - {$category->name}");
        $this->clearCache();
    }
}
