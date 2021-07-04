<?php


namespace App\Http\Requests\Post;


class UpdatePostRequest extends GetPostRequest
{
    protected $method = 'PATCH';

    protected $requiredAttributes = [
        'post_id', 'content', 'title', 'content_type'
    ];
}