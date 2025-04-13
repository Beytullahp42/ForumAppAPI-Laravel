<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function getLikes($postId)
    {
        $query = Vote::where('vote_type', 'like');

        $query->where('post_id', $postId);

        return response()->json(['likes' => $query->count()]);
    }

    public function getDislikes($postId)
    {
       $query = Vote::where('vote_type', 'dislike');

        $query->where('post_id', $postId);

        return response()->json(['dislikes' => $query->count()]);
    }

    public function like($postId)
    {
        $existingVote = Vote::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === 'like') {
                $existingVote->delete();
                return response()->json(['message' => 'Like removed']);
            } else {
                $existingVote->delete();
                Vote::create([
                    'user_id' => auth()->id(),
                    'post_id' => $postId,
                    'vote_type' => 'like',
                ]);
                return response()->json(['message' => 'Dislike removed, like added']);
            }
        } else {
            Vote::create([
                'user_id' => auth()->id(),
                'post_id' => $postId,
                'vote_type' => 'like',
            ]);
            return response()->json(['message' => 'Like added']);
        }
    }

    public function dislike($postId)
    {
        $existingVote = Vote::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === 'dislike') {
                $existingVote->delete();
                return response()->json(['message' => 'Dislike removed']);
            } else {
                $existingVote->delete();
                Vote::create([
                    'user_id' => auth()->id(),
                    'post_id' => $postId,
                    'vote_type' => 'dislike',
                ]);
                return response()->json(['message' => 'Like removed, dislike added']);
            }
        } else {
            Vote::create([
                'user_id' => auth()->id(),
                'post_id' => $postId,
                'vote_type' => 'dislike',
            ]);
            return response()->json(['message' => 'Dislike added']);
        }
    }


    public function isLiked($postId)
    {
        $liked = Vote::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->where('vote_type', 'like')
            ->exists();

        return response()->json(['is_liked' => $liked]);
    }

    public function isDisliked($postId)
    {
        $disliked = Vote::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->where('vote_type', 'dislike')
            ->exists();

        return response()->json(['is_disliked' => $disliked]);
    }
}
