<?php

namespace App\Repository;

use App\Models\Post;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostRepository
{
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        $page = request()->get('page', 1);

        return Cache::remember("posts.paginated.page{$page}.perpage{$perPage}", 1800, function () use ($perPage) {
            return Post::with(['user', 'category', 'comments.user'])
                ->latest()
                ->paginate($perPage);
        });
    }

    public function getByCategoryPaginated(int $categoryId, int $perPage = 10): LengthAwarePaginator
    {
        $page = request()->get('page', 1);

        return Cache::remember("posts.category.{$categoryId}.page{$page}.perpage{$perPage}", 1800, function () use ($categoryId, $perPage) {
            return Post::with(['user', 'category', 'comments.user'])
                ->where('category_id', $categoryId)
                ->latest()
                ->paginate($perPage);
        });
    }

    public function getUserPostsPaginated(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Post::with(['user', 'category', 'comments.user'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Post
    {
        return Cache::remember("post.{$id}", 1800, function () use ($id) {
            return Post::with(['user', 'category', 'comments.user'])->find($id);
        });
    }

    public function store(PostStoreRequest $request): Post
    {
        $data = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $imagePath
        ]);

        $this->clearCache();
        return $post;
    }

    public function update(Post $post, PostStoreRequest $request): bool
    {
        $data = $request->validated();

        $imagePath = $post->image;
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $result = $post->update([
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $imagePath
        ]);

        $this->clearCache();
        return $result;
    }

    public function destroy(Post $post): bool
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $result = $post->delete();
        $this->clearCache();
        return $result;
    }

    public function show(Post $post): array
    {
        return [
            'post' => $post->load(['user', 'category', 'comments.user']),
            'categories' => app(CategoryRepository::class)->getAllChildren()
        ];
    }

    private function clearCache(): void
    {
        Cache::flush();
    }
}
