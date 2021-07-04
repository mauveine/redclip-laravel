<?php


namespace App\Http\Requests\Comment;


class DeleteCommentRequest extends BaseCommentRequest
{
    protected $method = 'DELETE';

    protected $requiredAttributes = [
        'post_id', 'comment_id'
    ];
}