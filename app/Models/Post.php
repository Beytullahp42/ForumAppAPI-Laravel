<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'p_content', 'color', 'user_id'];
    protected $appends = ['like_count', 'dislike_count'];

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
    public function getLikeCountAttribute() {
        return $this->votes()->where('vote_type', 'like')->count();
    }

    public function getDislikeCountAttribute() {
        return $this->votes()->where('vote_type', 'dislike')->count();
    }


}
