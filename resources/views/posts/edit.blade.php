@extends('layouts.app')

@section('title', 'Редактировать пост')

@section('content')
    <h1 style="margin-bottom: 30px;">Редактировать пост</h1>

    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" style="max-width: 600px;">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Содержание:</label>
            <textarea id="content" name="content" class="form-control" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Изображение (опционально):</label>
            <input type="file" id="image" name="image" class="form-control">
            @if($post->image)
                <div style="margin-top: 10px;">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Current image" style="max-width: 200px;">
                    <p style="font-size: 12px; color: #666;">Текущее изображение</p>
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Обновить пост</button>
        <a href="{{ route('posts.index') }}" class="btn btn-primary">Назад к постам</a>
    </form>
@endsection
