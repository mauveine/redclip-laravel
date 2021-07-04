<?php


namespace App\Http\Requests\Comment;


use App\Models\Comment;
use App\Models\Post;

class VoteCommentRequest extends BaseCommentRequest
{
    protected $method = 'POST';

    protected $requiredAttributes = [
        'postId', 'commentId'
    ];

    public function authorize (): bool {
        $post = Post::find($this->route('postId'));
        if (!$post) {
            return false;
        }

        $comment = Comment::find($this->route('commentId'));
        if (!$comment) {
            return false;
        }

        return !$comment->voted($this->session()->get('username'));
    }
}