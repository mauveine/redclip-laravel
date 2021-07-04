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
        return $this->hasMany(Vote::class, 'post_id', 'id');
    }

    public function myVote () {
        return $this->hasMany(Vote::class, 'post_id', 'id')->where('username', session()->get('username'));
    }
}
