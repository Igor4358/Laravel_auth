<div class="post-card">
    <div class="post-header">
        <h2 class="post-title">{{ $post->title }}</h2>
        <div class="post-meta">
            Автор: {{ $post->user->name }} |
            {{ $post->created_at->format('d.m.Y H:i') }}
            @if($post->category)
                | Категория: <a href="{{ route('categories.show', $post->category->slug) }}">{{ $post->category->name }}</a>
            @endif
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

    <!-- Действия с постом и комментарии остаются без изменений -->
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

    <!-- Комментарии -->
    <div class="comments-section" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <!-- ... код комментариев без изменений ... -->
    </div>
</div>
