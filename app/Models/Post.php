<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'title', 'content', 'content_type', 'created_at'
    ];

    public function voted ($username) {
        return $this->votes()->where('username', '=', $username)->count(['id']) > 0;
    }

    public function votes () {
        return $this->hasMany(Vote::class, 'post_id', 'id')->whereNotNull('post_id')->whereNull('comment_id');
    }

    public function comments () {
        return $this->hasMany(Comment::class, 'post_id', 'id')->whereNull('comment_id');
    }

    public function myVote () {
        $sessionName = session()->get('username');
        $relationship = $this->hasOne(Vote::class, 'post_id', 'id')
            ->whereNotNull('post_id')->whereNull('comment_id');
        return $sessionName ? $relationship->where('username', '=', $sessionName) : $relationship;
    }
}
