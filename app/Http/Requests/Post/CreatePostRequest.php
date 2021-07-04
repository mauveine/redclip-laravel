<?php


namespace App\Http\Requests\Post;


class CreatePostRequest extends BasePostRequest
{
    protected $method = 'POST';

    protected $requiredAttributes = [
        'username', 'title', 'content', 'content_type'
    ];
}