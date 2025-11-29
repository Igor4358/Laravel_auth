<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Блог')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: #343a40;
            color: white;
            padding: 15px 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links {
            display: flex;
            list-style: none;
        }
        .nav-links li {
            margin-right: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .welcome {
            color: #ccc;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .main-content {
            padding: 30px 0;
        }
        .post-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .post-title {
            font-size: 24px;
            color: #333;
        }
        .post-meta {
            color: #666;
            font-size: 14px;
        }
        .post-content {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .post-actions {
            display: flex;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        .pagination {
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            gap: 5px !important;
            margin: 30px 0 !important;
        }

        .pagination li {
            display: inline-block !important;
        }

        .pagination a,
        .pagination span {
            display: inline-block !important;
            padding: 8px 12px !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            text-decoration: none !important;
            color: #007bff !important;
            min-width: 40px !important;
            text-align: center !important;
        }

        .pagination .active span {
            background: #007bff !important;
            color: white !important;
        }

        .pagination .sr-only {
            display: none !important;
        }

        .pagination li:not(.disabled):not(.active) a,
        .pagination li.active span,
        .pagination li.disabled span {
            display: inline-block !important;
        }
        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="container">
        <nav class="nav">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Главная</a></li>
                <li><a href="{{ route('posts.index') }}">Посты</a></li>
                <li><a href ="{{route('categories.index')}}">Категории</a></li>
                <li><a href="{{ route('users.index') }}">Пользователи</a></li>
                <li><a href="{{route('about')}}">О нас</a></li>
            </ul>

            <div class="user-info">
                @auth
                    @if(auth()->user()->profile && auth()->user()->profile->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="Avatar" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/35" alt="Default Avatar" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                    @endif
                    <span class="welcome">Добро пожаловать, {{ auth()->user()->name }}!</span>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Профиль</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login.view') }}" class="btn btn-primary">Войти</a>
                    <a href="{{ route('register.view') }}" class="btn btn-success">Регистрация</a>
                @endauth
            </div>
        </nav>
    </div>
</header>

<main class="main-content">
    <div class="container">
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>
</body>
</html>
