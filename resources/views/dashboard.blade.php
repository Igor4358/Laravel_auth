@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <h1 style="margin-bottom: 30px;">Личный кабинет</h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <div class="post-card">
            <h2 style="margin-bottom: 20px;">Мой профиль</h2>

            <!-- Аватар -->
            @if(auth()->user()->profile && auth()->user()->profile->avatar)
                <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
            @else
                <img src="https://via.placeholder.com/150" alt="Default Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
            @endif

            <!-- Информация о пользователе -->
            <h3>{{ auth()->user()->name }}</h3>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

            @if(auth()->user()->profile && auth()->user()->profile->bio)
                <p><strong>О себе:</strong> {{ auth()->user()->profile->bio }}</p>
            @endif
            <div style="margin-top: 20px;">
                <a href="{{ route('user.profile') }}" class="btn btn-primary" style="display: block; text-align: center;">Редактировать профиль</a>
            </div>
            <!-- Статистика -->
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                <h4>Статистика:</h4>
                <p>Постов: {{ auth()->user()->posts->count() }}</p>
            </div>
        </div>

        <!-- Мои посты -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Мои посты</h2>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Добавить пост</a>
            </div>

            @if(auth()->user()->posts->count() > 0)
                @foreach(auth()->user()->posts as $post)
                    <div class="post-card">
                        <div class="post-header">
                            <h3 class="post-title">{{ $post->title }}</h3>
                            <div class="post-meta">
                                {{ $post->created_at->format('d.m.Y H:i') }}
                                @if($post->updated_at != $post->created_at)
                                    (изменен: {{ $post->updated_at->format('d.m.Y H:i') }})
                                @endif
                            </div>
                        </div>

                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="post-image">
                        @endif

                        <div class="post-content">
                            {{ Str::limit($post->content, 200) }}
                        </div>

                        <div class="post-actions">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">Редактировать</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить пост?')">Удалить</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="post-card" style="text-align: center;">
                    <h3>У вас пока нет постов</h3>
                    <p><a href="{{ route('posts.create') }}">Создайте первый пост!</a></p>
                </div>
            @endif
        </div>
    </div>
@endsection

