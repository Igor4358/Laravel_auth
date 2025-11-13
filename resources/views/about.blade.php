@extends('layouts.app')

@section('title', 'О нас')

@section('content')
    <div class="post-card">
        <h1 style="font-size: 32px; font-weight: bold; margin-bottom: 20px; text-align: center;">О нас</h1>

        <div style="line-height: 1.6;">
            <p style="color: #333; margin-bottom: 15px; font-size: 16px;">
                Добро пожаловать в наш блог-проект! Это платформа для общения и обмена мыслями.
            </p>

            <h2 style="font-size: 24px; font-weight: 600; margin: 25px 0 15px 0; color: #333;">Возможности</h2>
            <ul style="list-style-type: disc; margin-left: 20px; color: #333; space-y: 8px;">
                <li style="margin-bottom: 8px;">Создание и публикация постов</li>
                <li style="margin-bottom: 8px;">Просмотр постов других пользователей</li>
                <li style="margin-bottom: 8px;">Редактирование профиля с аватаром</li>
                <li style="margin-bottom: 8px;">Система авторизации и регистрации</li>
                <li style="margin-bottom: 8px;">Поиск пользователей</li>
                <li style="margin-bottom: 8px;">Загрузка изображений к постам</li>
            </ul>

            <h2 style="font-size: 24px; font-weight: 600; margin: 25px 0 15px 0; color: #333;">Технологии</h2>
            <p style="color: #333;">
                Проект разработан на Laravel с использованием SQLite базы данных.
            </p>

            <h2 style="font-size: 24px; font-weight: 600; margin: 25px 0 15px 0; color: #333;">Наша команда</h2>
            <p style="color: #333;">
                Мы - команда энтузиастов, создающая удобную платформу для общения и обмена идеями.
            </p>
        </div>
    </div>
@endsection
