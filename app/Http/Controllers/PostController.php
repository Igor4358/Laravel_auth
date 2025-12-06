<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private const PER_PAGE_ITEMS = 10;

    private PostRepository $postRepository;
    private CategoryRepository $categoryRepository;
    private PostService $postService;

    // Убираем конструктор с dependency injection
    // Вместо этого используем сервис контейнер Laravel

    private function getPostRepository(): PostRepository
    {
        if (!isset($this->postRepository)) {
            $this->postRepository = app(PostRepository::class);
        }
        return $this->postRepository;
    }

    private function getCategoryRepository(): CategoryRepository
    {
        if (!isset($this->categoryRepository)) {
            $this->categoryRepository = app(CategoryRepository::class);
        }
        return $this->categoryRepository;
    }

    private function getPostService(): PostService
    {
        if (!isset($this->postService)) {
            $this->postService = app(PostService::class);
        }
        return $this->postService;
    }

    public function index(): View
    {
        return view('posts.index', [
            'posts' => $this->getPostRepository()->getAllPaginated(self::PER_PAGE_ITEMS),
            'categories' => $this->getCategoryRepository()->getAllChildren()
        ]);
    }

    public function create(): View
    {
        return view('posts.create', [
            'categories' => $this->getCategoryRepository()->getAllChildren()
        ]);
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $this->getPostRepository()->store($request);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Пост успешно создан!');
    }

    public function show(Post $post): View
    {
        $postData = $this->getPostRepository()->show($post);

        return view('posts.show', array_merge($postData, [
            'latestPosts' => $this->getPostService()->getLatestPosts(5)
        ]));
    }

    public function edit(Post $post): View
    {
        // Проверка прав
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав для редактирования этого поста');
        }

        return view('posts.edit', [
            'post' => $this->getPostService()->getPostByCache($post->id),
            'categories' => $this->getCategoryRepository()->getAllChildren()
        ]);
    }

    public function update(PostStoreRequest $request, Post $post): RedirectResponse
    {
        // Проверка прав
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав для обновления этого поста');
        }

        $this->getPostRepository()->update($post, $request);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Пост успешно обновлен!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        // Проверка прав
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав для удаления этого поста');
        }

        $this->getPostRepository()->destroy($post);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Пост успешно удален!');
    }
}
