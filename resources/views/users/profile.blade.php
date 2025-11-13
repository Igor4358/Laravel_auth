@extends('layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
    <h1 style="margin-bottom: 30px;">Редактирование профиля</h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Текущий профиль -->
        <div class="post-card">
            <h2 style="margin-bottom: 20px;">Текущий профиль</h2>

            <!-- Аватар -->
            <div style="text-align: center; margin-bottom: 20px;">
                @if(auth()->user()->profile && auth()->user()->profile->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">
                    <form method="POST" action="{{ route('user.removeAvatar') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Удалить аватар?')">Удалить аватар</button>
                    </form>
                @else
                    <img src="https://via.placeholder.com/150" alt="Default Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">
                    <p style="color: #666;">Аватар не установлен</p>
                @endif
            </div>

            <h3>{{ auth()->user()->name }}</h3>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

            @if(auth()->user()->profile && auth()->user()->profile->bio)
                <p><strong>О себе:</strong> {{ auth()->user()->profile->bio }}</p>
            @endif

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                <p><strong>Зарегистрирован:</strong> {{ auth()->user()->created_at->format('d.m.Y') }}</p>
                <p><strong>Постов:</strong> {{ auth()->user()->posts->count() }}</p>
            </div>
        </div>

        <!-- Форма редактирования -->
        <div class="post-card">
            <h2 style="margin-bottom: 20px;">Редактировать профиль</h2>

            <form method="POST" action="{{ route('user.updateProfile') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Новый пароль (оставьте пустым, если не хотите менять):</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Подтвердите новый пароль:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label for="avatar">Новый аватар:</label>
                    <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                    <small style="color: #666;">Формат: JPEG, PNG, JPG, GIF. Максимальный размер: 2MB</small>
                </div>

                <div class="form-group">
                    <label for="bio">О себе:</label>
                    <textarea id="bio" name="bio" class="form-control" rows="4">{{ old('bio', auth()->user()->profile->bio ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">Сохранить изменения</button>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Назад в профиль</a>
            </form>
        </div>
    </div>
@endsection
