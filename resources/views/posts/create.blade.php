@extends('layouts.app')

@section('title', 'Создать пост')

@section('content')
    <h1 style="margin-bottom: 30px;">Создать новый пост</h1>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" style="max-width: 600px;">
        @csrf

        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="content">Содержание:</label>
            <textarea id="content" name="content" class="form-control" required>{{ old('content') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Изображение (опционально):</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Создать пост</button>
        <a href="{{ route('posts.index') }}" class="btn btn-primary">Назад к постам</a>
    </form>
@endsection
