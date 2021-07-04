<?php


namespace App\Http\Requests\Comment;


class GetCommentRequest extends BaseCommentRequest
{
    protected $method = 'GET';
    protected $requiredAttributes = [
        'post_id', 'comment_id'
    ];
}