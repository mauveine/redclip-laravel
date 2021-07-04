<?php


namespace App\Http\Requests\Post;


class DeletePostRequest extends GetPostRequest
{
    protected $method = 'DELETE';
}