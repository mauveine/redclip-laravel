<?php


namespace App\Http\Requests\Post;


class GetPostRequest extends BasePostRequest
{
    protected $method = 'GET';

    protected $requiredAttributes = [
        'post_id'
    ];
}