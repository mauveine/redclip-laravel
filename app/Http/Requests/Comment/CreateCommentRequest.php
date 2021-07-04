<?php


namespace App\Http\Requests\Comment;


class CreateCommentRequest extends BaseCommentRequest
{
    protected $method = 'POST';

    protected $requiredAttributes = [
        'username', 'reply', 'post_id'
    ];
}