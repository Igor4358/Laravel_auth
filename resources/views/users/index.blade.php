@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <h1 style="margin-bottom: 30px;">Все пользователи</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @foreach($users as $user)
            <div class="post-card">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <!-- Аватар пользователя -->
                    @if($user->profile && $user->profile->avatar)
                        <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="Avatar" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                    @else
                        <img src="https://via.placeholder.com/60" alt="Default Avatar" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
                    @endif

                    <div>
                        <h3 style="margin: 0; color: #333;">{{ $user->name }}</h3>
                        <p style="margin: 0; color: #666; font-size: 14px;">{{ $user->email }}</p>
                    </div>
                </div>

                @if($user->profile && $user->profile->bio)
                    <p style="color: #666; margin-bottom: 15px;">{{ Str::limit($user->profile->bio, 100) }}</p>
                @endif

                <div style="display: flex; justify-content: space-between; color: #888; font-size: 14px;">
                    <span>Постов: {{ $user->posts->count() }}</span>
                    <span>Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}</span>
                </div>

                <a href="{{ route('users.show', $user) }}" class="btn btn-primary" style="margin-top: 15px; display: block; text-align: center;">Профиль</a>
            </div>
        @endforeach
    </div>
@endsection
