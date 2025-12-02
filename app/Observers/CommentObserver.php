<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CommentObserver
{
    /**
     * Очистка кэша при любых изменениях комментариев
     */
    private function clearCache(): void
    {
        Cache::forget('posts.all');
        Cache::forget('posts.latest');

        Log::info('Comment cache cleared');
    }

    public function created(Comment $comment): void
    {
        Log::info("Comment created: {$comment->id} for post: {$comment->post_id}");
        $this->clearCache();
    }

    public function updated(Comment $comment): void
    {
        Log::info("Comment updated: {$comment->id} for post: {$comment->post_id}");
        $this->clearCache();
    }

    public function deleted(Comment $comment): void
    {
        Log::info("Comment deleted: {$comment->id} for post: {$comment->post_id}");
        $this->clearCache();
    }

    public function restored(Comment $comment): void
    {
        Log::info("Comment restored: {$comment->id} for post: {$comment->post_id}");
        $this->clearCache();
    }

    public function forceDeleted(Comment $comment): void
    {
        Log::info("Comment force deleted: {$comment->id} for post: {$comment->post_id}");
        $this->clearCache();
    }
}
