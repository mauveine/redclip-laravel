<?php


namespace App\Http\Requests\Comment;


class UpdateCommentRequest extends BaseCommentRequest
{
    protected $method = 'PATCH';

    protected $requiredAttributes = [
        'username', 'reply', 'post_id', 'comment_id'
    ];
}