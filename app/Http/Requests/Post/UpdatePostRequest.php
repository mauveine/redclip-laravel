<?php


namespace App\Http\Requests\Post;


class UpdatePostRequest extends DeletePostRequest
{
    protected $method = 'PATCH';

    protected $requiredAttributes = [
        'post_id', 'content', 'title', 'content_type'
    ];
}