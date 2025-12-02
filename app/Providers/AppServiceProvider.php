<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CategoryObserver;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
        Category::observe(CategoryObserver::class);
    }
}
