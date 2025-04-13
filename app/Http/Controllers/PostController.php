<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::with(['user'])->paginate(10);
        return response()->json($posts);
    }

    public function get($id)
    {
        $post = Post::with('user')->find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json($post);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'p_content' => 'required|string',
            'color' => 'nullable|string|max:20',
        ]);
        $post = Post::create([
            'title' => $request->title,
            'p_content' => $request->p_content,
            'color' => $request->color ?? 'white',
            'user_id' => auth()->id(),
        ]);
        return response()->json($post, 201);
    }

    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'You are not authorized to delete this post'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
