<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'p_content', 'color', 'user_id'];
    protected $appends = ['like_count', 'dislike_count', 'is_liked', 'is_disliked'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Accessor for like count
    public function getLikeCountAttribute() {
        return $this->votes()->where('vote_type', 'like')->count();
    }

    // Accessor for dislike count
    public function getDislikeCountAttribute() {
        return $this->votes()->where('vote_type', 'dislike')->count();
    }

    // Accessor to check if the authenticated user liked the post
    public function getIsLikedAttribute()
    {
        return $this->votes()->where('user_id', Auth::id())->where('vote_type', 'like')->exists();
    }

    // Accessor to check if the authenticated user disliked the post
    public function getIsDislikedAttribute()
    {
        return $this->votes()->where('user_id', Auth::id())->where('vote_type', 'dislike')->exists();
    }
}
