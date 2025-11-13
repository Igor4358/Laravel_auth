@extends('layouts.app')

@section('title', 'Все посты')
@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Все посты</h1>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">Добавить пост</a>
        @endauth
    </div>

    @if($posts->count() > 0)
        @foreach($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <h2 class="post-title">{{ $post->title }}</h2>
                    <div class="post-meta">
                        Автор: {{ $post->user->name }} |
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
                    {{ $post->content }}
                </div>

                @auth
                    @if($post->user_id == auth()->id())
                        <div class="post-actions">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">Редактировать</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить пост?')">Удалить</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @endforeach
        <div class="pagination">
            {{ $posts->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; background: white; border-radius: 8px;">
            <h3>Пока нет постов</h3>
            @auth
                <p><a href="{{ route('posts.create') }}">Создайте первый пост!</a></p>
            @else
                <p><a href="{{ route('login.view') }}">Войдите</a>, чтобы создать пост</p>
            @endauth
        </div>
    @endif
@endsection
