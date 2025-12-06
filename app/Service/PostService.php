<?php

namespace App\Service;

use App\Models\Post;
use App\Repository\PostRepository;
use Illuminate\Support\Facades\Cache;

class PostService
{
    public function __construct(
        private readonly PostRepository $postRepository
    )
    {
    }

    public function getPostByCache(int $postId): ?Post
    {
        return Cache::remember("post.service.{$postId}", 1800, function () use ($postId) {
            return Post::with(['user', 'category', 'comments.user'])->find($postId);
        });
    }

    public function getLatestPosts(int $limit = 10)
    {
        return Cache::remember("posts.latest.{$limit}", 900, function () use ($limit) {
            return Post::with(['user', 'category'])->latest()->limit($limit)->get();
        });
    }

    public function getPopularPosts(int $limit = 10)
    {
        return Cache::remember("posts.popular.{$limit}", 3600, function () use ($limit) {
            return Post::withCount('comments')
                ->with(['user', 'category'])
                ->orderBy('comments_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}
