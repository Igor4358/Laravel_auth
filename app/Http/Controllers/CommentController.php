<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'content' => $validated['content']
        ]);

        return redirect()->route('posts.index')->with('success', 'Комментарий добавлен!');
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого комментария');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update([
            'content' => $validated['content']
        ]);

        return redirect()->route('posts.index')->with('success', 'Комментарий обновлен!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для удаления этого комментария');
        }

        $comment->delete();

        return redirect()->route('posts.index')->with('success', 'Комментарий удален!');
    }
}
