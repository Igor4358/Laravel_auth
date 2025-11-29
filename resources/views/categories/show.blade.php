@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <h1 style="margin-bottom: 30px;">Категория: {{ $category->name }}</h1>

    @if($category->description)
        <div class="post-card" style="margin-bottom: 20px;">
            <p>{{ $category->description }}</p>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px;">
        <!-- Боковая панель с деревом категорий -->
        <div>
            <div class="post-card">
                <h3 style="margin-bottom: 20px;">Категории</h3>
                @include('categories.partials.tree', ['categories' => $categories])
            </div>
        </div>

        <!-- Список постов в категории -->
        <div>
            <h2 style="margin-bottom: 20px;">
                Посты в категории "{{ $category->name }}"
                <span style="font-size: 14px; color: #666;">({{ $posts->count() }})</span>
            </h2>

            @if($posts->count() > 0)
                @foreach($posts as $post)
                    @include('posts.partials.post-card', ['post' => $post])
                @endforeach
            @else
                <div class="post-card" style="text-align: center;">
                    <h3>В этой категории пока нет постов</h3>
                    @auth
                        <p><a href="{{ route('posts.create') }}">Создайте первый пост в этой категории!</a></p>
                    @endauth
                </div>
            @endif
        </div>
    </div>
@endsection
