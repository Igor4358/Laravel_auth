@extends('layouts.app')

@section('title', 'Категории')

@section('content')
    <h1 style="margin-bottom: 30px;">Категории</h1>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px;">
        <!-- Боковая панель с деревом категорий -->
        <div>
            <div class="post-card">
                <h3 style="margin-bottom: 20px;">Категории</h3>
                @include('categories.partials.tree', ['categories' => $categories])
            </div>
        </div>

        <!-- Список постов -->
        <div>
            <h2 style="margin-bottom: 20px;">Все посты</h2>

            @if($posts->count() > 0)
                @foreach($posts as $post)
                    @include('posts.partials.post-card', ['post' => $post])
                @endforeach
            @else
                <div class="post-card" style="text-align: center;">
                    <h3>Пока нет постов</h3>
                    @auth
                        <p><a href="{{ route('posts.create') }}">Создайте первый пост!</a></p>
                    @else
                        <p><a href="{{ route('login.view') }}">Войдите</a>, чтобы создать пост</p>
                    @endauth
                </div>
            @endif
        </div>
    </div>
@endsection
