<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getAll($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comments = Comment::where('post_id', $postId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($comments);
    }

    public function get($id)
    {
        $comment = Comment::with('user')->find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment);
    }

    public function create(Request $request, $postId)
    {
        $request->validate([
            'p_content' => 'required|string|max:500',
        ]);
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $comment = Comment::create([
            'p_content' => $request->p_content,
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);
        return response()->json($comment, 201);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'You are not authorized to delete this comment'], 403);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
