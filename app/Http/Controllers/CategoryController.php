<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Используем кэшированные посты
        $posts = $category->getAllPosts()->load('user', 'category', 'comments.user');
        $categories = Category::getTree();

        return view('categories.show', compact('category', 'posts', 'categories'));
    }

    public function index()
    {
        // Используем кэшированные данные
        $categories = Category::getTree();
        $posts = Post::getAllCached();

        return view('categories.index', compact('categories', 'posts'));
    }
}
