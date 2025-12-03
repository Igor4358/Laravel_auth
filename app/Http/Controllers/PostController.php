<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        // Используем пагинацию с загрузкой комментариев и их авторов
        $posts = Post::with(['user', 'category', 'comments.user'])
        ->latest()
            ->paginate(10);

        $categories = Category::getTree();

        return view('posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::getTree();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Log::info('Post creation attempt', ['user_id' => auth()->id(), 'title' => $request->title]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');

            Log::info('Post image uploaded', ['path' => $imagePath]);
        }

        // Создание поста автоматически вызовет Observer
        Post::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath
        ]);

        return redirect()->route('posts.index')->with('success', 'Пост создан успешно!');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого поста');
        }

        // Добавляем загрузку категорий
        $categories = Category::getTree();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $post->image;
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'image' => $imagePath
        ]);
        Log::info('Post update attempt', ['post_id' => $post->id, 'user_id' => auth()->id()]);
        return redirect()->route('posts.index')->with('success', 'Пост обновлен успешно!');
    }
    public function show(Post $post)
    {
        $post->load('user');
        return view('posts.show', compact('post'));
    }
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        Log::info('Post deletion attempt', ['post_id' => $post->id, 'user_id' => auth()->id()]);
        // Удаляем изображение
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        Log::info('Post deleted', ['post_id' => $post->id, 'user_id' => auth()->id()]);
        return redirect()->route('posts.index')->with('success', 'Пост удален успешно!');
    }
}
