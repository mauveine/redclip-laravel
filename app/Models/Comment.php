<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'reply', 'post_id', 'comment_id'
    ];

    public function voted ($username) {
        return $this->votes()->where('username', '=', $username)->count(['id']) > 0;
    }

    public function votes () {
        return $this->hasMany(Vote::class, 'comment_id', 'id')->whereNotNull('post_id')->whereNotNull('comment_id');
    }

    public function myVote () {
        return $this->hasMany(Vote::class, 'comment_id', 'id')
            ->where(function($query) {
                $query->orWhereNotNull('post_id');
                $query->orWhereNotNull('comment_id');
            })
            ->where('username', session()->get('username'));
    }

    public function post () {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function comment () {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function replies () {
        return $this->hasMany(Comment::class, 'comment_id', 'id')->withCount(['myVote', 'votes']);
    }
}
