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

    <!-- Действия с постом -->
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
        <h4>Комментарии ({{ $post->comments->count() }}):</h4>

        <!-- Список комментариев -->
        @foreach($post->comments as $comment)
            <div class="comment" style="background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="flex: 1;">
                        <strong>{{ $comment->user->name }}</strong>
                        <small style="color: #666; margin-left: 10px;">
                            {{ $comment->created_at->format('d.m.Y H:i') }}
                        </small>
                        <p style="margin: 5px 0 0 0;">{{ $comment->content }}</p>
                    </div>

                    <!-- Действия с комментарием -->
                    @auth
                        @if($comment->user_id == auth()->id())
                            <div style="display: flex; gap: 5px;">
                                <!-- Форма редактирования -->
                                <form method="POST" action="{{ route('comments.update', $comment) }}" style="display: none;" id="edit-form-{{ $comment->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="content" value="{{ $comment->content }}" style="width: 200px; padding: 5px;">
                                    <button type="submit" class="btn btn-success btn-sm">✓</button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelEdit({{ $comment->id }})">✗</button>
                                </form>

                                <!-- Кнопки действий -->
                                <div id="comment-actions-{{ $comment->id }}">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="enableEdit({{ $comment->id }})">✎</button>
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить комментарий?')">🗑</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach

        <!-- Форма добавления комментария -->
        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}" style="margin-top: 20px;">
                @csrf
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="content" placeholder="Добавить комментарий..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
        @else
            <p style="margin-top: 15px; color: #666;">
                <a href="{{ route('login.view') }}">Войдите</a>, чтобы оставить комментарий
            </p>
        @endauth
    </div>
</div>

<script>
    function enableEdit(commentId) {
        document.getElementById('edit-form-' + commentId).style.display = 'block';
        document.getElementById('comment-actions-' + commentId).style.display = 'none';
    }

    function cancelEdit(commentId) {
        document.getElementById('edit-form-' + commentId).style.display = 'none';
        document.getElementById('comment-actions-' + commentId).style.display = 'block';
    }
</script>
