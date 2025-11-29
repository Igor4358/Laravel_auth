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

        $posts = $category->getAllPosts()->load('user', 'category', 'comments');

        $categories = Category::getTree();

        return view('categories.show', compact('category', 'posts', 'categories'));
    }

    public function index()
    {
        $categories = Category::getTree();
        $posts = Post::with('user', 'category', 'comments')->latest()->get();

        return view('categories.index', compact('categories', 'posts'));
    }
}
