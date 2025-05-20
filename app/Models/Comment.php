<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['p_content', 'post_id', 'user_id'];
    protected $appends = ['like_count', 'dislike_count', 'is_liked', 'is_disliked'];

    protected $casts = [
        'id' => 'integer',
        'post_id' => 'integer',
        'user_id' => 'integer',
        'like_count' => 'integer',
        'dislike_count' => 'integer',
        'is_liked' => 'boolean',
        'is_disliked' => 'boolean',
        'p_content' => 'string',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public function getLikeCountAttribute() {
        return $this->votes()->where('vote_type', 'like')->count();
    }

    public function getDislikeCountAttribute() {
        return $this->votes()->where('vote_type', 'dislike')->count();
    }

    public function getIsLikedAttribute()
    {
        return $this->votes()->where('user_id', auth()->id())->where('vote_type', 'like')->exists();
    }

    public function getIsDislikedAttribute()
    {
        return $this->votes()->where('user_id', auth()->id())->where('vote_type', 'dislike')->exists();
    }
}
