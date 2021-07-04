<?php


namespace App\Http\Requests\Comment;


use App\Models\Comment;

class DeleteCommentRequest extends BaseCommentRequest
{
    protected $method = 'DELETE';

    protected $requiredAttributes = [
        'post_id', 'comment_id'
    ];

    public function authorize (): bool {
        $currentUser = $this->session()->get('username');
        $comment = Comment::find($this->route('commentId'));
        if (!$comment) {
            return false;
        }

        return $comment->username === $currentUser;
    }
}