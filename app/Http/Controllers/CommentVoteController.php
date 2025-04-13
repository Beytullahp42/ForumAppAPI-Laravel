<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;

class CommentVoteController extends Controller
{
    public function getLikes($commentId)
    {
        $query = CommentVote::where('vote_type', 'like');

        $query->where('comment_id', $commentId);

        return response()->json(['likes' => $query->count()]);
    }

    public function getDislikes($commentId)
    {
        $query = CommentVote::where('vote_type', 'dislike');

        $query->where('comment_id', $commentId);

        return response()->json(['dislikes' => $query->count()]);
    }

    public function like($commentId)
    {
        $existingVote = CommentVote::where('user_id', auth()->id())
            ->where('comment_id', $commentId)
            ->first();

        $postId = Comment::findOrFail($commentId)->post->id;

        if ($existingVote) {
            if ($existingVote->vote_type === 'like') {
                $existingVote->delete();
                return response()->json(['message' => 'Like removed']);
            } else {
                $existingVote->delete();
                CommentVote::create([
                    'user_id' => auth()->id(),
                    'post_id' => $postId,
                    'comment_id' => $commentId,
                    'vote_type' => 'like',
                ]);
                return response()->json(['message' => 'Dislike removed, like added']);
            }
        } else {
            CommentVote::create([
                'user_id' => auth()->id(),
                'post_id' => $postId,
                'comment_id' => $commentId,
                'vote_type' => 'like',
            ]);
            return response()->json(['message' => 'Like added']);
        }
    }

    public function dislike($commentId)
    {
        $existingVote = CommentVote::where('user_id', auth()->id())
            ->where('comment_id', $commentId)
            ->first();

        $postId = Comment::findOrFail($commentId)->post->id;


        if ($existingVote) {
            if ($existingVote->vote_type === 'dislike') {
                $existingVote->delete();
                return response()->json(['message' => 'Dislike removed']);
            } else {
                $existingVote->delete();
                CommentVote::create([
                    'user_id' => auth()->id(),
                    'post_id' => $postId,
                    'comment_id' => $commentId,
                    'vote_type' => 'dislike',
                ]);
                return response()->json(['message' => 'Like removed, dislike added']);
            }
        } else {
            CommentVote::create([
                'user_id' => auth()->id(),
                'post_id' => $postId,
                'comment_id' => $commentId,
                'vote_type' => 'dislike',
            ]);
            return response()->json(['message' => 'Dislike added']);
        }
    }

    public function isLiked($commentId)
    {
        $liked = CommentVote::where('user_id', auth()->id())
            ->where('comment_id', $commentId)
            ->where('vote_type', 'like')
            ->exists();

        return response()->json(['is_liked' => $liked]);
    }

    public function isDisliked($commentId)
    {
        $disliked = CommentVote::where('user_id', auth()->id())
            ->where('comment_id', $commentId)
            ->where('vote_type', 'dislike')
            ->exists();

        return response()->json(['is_disliked' => $disliked]);
    }
}
