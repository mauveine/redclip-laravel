<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'reply', 'post_id'
    ];

    public function voted ($username) {
        return $this->votes()->where('username', '=', $username)->count(['id']) > 0;
    }

    public function votes () {
        return $this->hasMany(Vote::class, 'comment_id', 'id');
    }
}
