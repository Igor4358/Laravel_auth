@extends('layouts.app')

@section('title', 'Профиль ' . $user->name)

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Профиль пользователя -->
        <div class="post-card">
            <div style="text-align: center;">
                <!-- Аватар -->
                @if($user->profile && $user->profile->avatar)
                    <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
                @else
                    <img src="https://via.placeholder.com/150" alt="Default Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px;">
                @endif

                <h2>{{ $user->name }}</h2>
                <p style="color: #666; margin-bottom: 15px;">{{ $user->email }}</p>

                @if($user->profile && $user->profile->bio)
                    <p style="color: #333; font-style: italic;">"{{ $user->profile->bio }}"</p>
                @endif

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <p><strong>Зарегистрирован:</strong> {{ $user->created_at->format('d.m.Y') }}</p>
                    <p><strong>Всего постов:</strong> {{ $user->posts->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Посты пользователя -->
        <div>
            <h2 style="margin-bottom: 20px;">Посты пользователя</h2>

            @if($user->posts->count() > 0)
                @foreach($user->posts as $post)
                    <div class="post-card">
                        <div class="post-header">
                            <h3 class="post-title">{{ $post->title }}</h3>
                            <div class="post-meta">
                                {{ $post->created_at->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="post-image">
                        @endif

                        <div class="post-content">
                            {{ Str::limit($post->content, 200) }}
                        </div>

                        @if($post->content != Str::limit($post->content, 200))
                            <a href="{{ route('posts.show', $post) }}" style="color: #007bff; text-decoration: none;">Читать далее...</a>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="post-card" style="text-align: center;">
                    <h3>У пользователя пока нет постов</h3>
                </div>
            @endif
        </div>
    </div>
@endsection
