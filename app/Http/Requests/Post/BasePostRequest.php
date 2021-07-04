<?php


namespace App\Http\Requests\Post;


use App\Http\Requests\BaseRequest;
use App\Models\Post;
use Illuminate\Validation\Rule;

abstract class BasePostRequest extends BaseRequest
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
            'username' => [
                'string'
            ],
            'title' => [
                'string'
            ],
            'content' => [
                'string',
                'max:1000'
            ],
            'content_type' => [
                'string',
                Rule::in(['text', 'img', 'video'])
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
        return array_merge($result, [
            'username' => $this->session()->get('username')
        ]);
    }
}