<?php


namespace App\Http\Requests\Comment;


use App\Http\Requests\BaseRequest;
use App\Models\Comment;
use App\Models\Post;

abstract class BaseCommentRequest extends BaseRequest
{
    public function authorize () :bool {
        return true;
    }

    public function buildDefaultRules () {
        return [
            'post_id' => [
                'numeric',
                sprintf('exists:%s,id', Post::class)
            ],
            'comment_id' => [
                'numeric',
                sprintf('exists:%s,id', Comment::class)
            ],
            'username' => [
                'string'
            ],
            'reply' => [
                'string',
                'max:255'
            ]
        ];
    }

    public function all($keys = null) :array
    {
        $result = parent::all($keys);
        if ($this->route('postId')) {
            $result = array_merge($result, [
                'post_id' => $this->route('postId'),
            ]);
        }

        if ($this->route('commentId')) {
            $result = array_merge($result, [
                'comment_id' => $this->route('commentId'),
            ]);
        }
        return array_merge($result, [
            'username' => $this->session()->get('username')
        ]);
    }
}