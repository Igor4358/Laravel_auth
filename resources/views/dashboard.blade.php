<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        .profile-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .avatar { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 20px; }
        .logout-btn { background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: green; margin-bottom: 15px; padding: 10px; background: #d4edda; border-radius: 4px; }
    </style>
</head>
<body>
<div class="profile-card">
    <h2>Личный кабинет</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @auth
        <!-- Аватар -->
        @if(auth()->user()->profile && auth()->user()->profile->avatar)
            <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Avatar" class="avatar">
        @else
            <img src="https://via.placeholder.com/150" alt="Default Avatar" class="avatar">
        @endif

        <h3>{{ auth()->user()->name }}</h3>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

        @if(auth()->user()->profile && auth()->user()->profile->bio)
            <p><strong>О себе:</strong> {{ auth()->user()->profile->bio }}</p>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Выйти</button>
        </form>
    @else
        <p>Пожалуйста, <a href="{{ route('login.view') }}">войдите</a> в систему.</p>
    @endauth
</div>
</body>
</html>
