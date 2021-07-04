<?php


namespace App\Http\Requests\Post;


use App\Models\Post;

class VotePostRequest extends BasePostRequest
{
    protected $method = 'POST';

    protected $requiredAttributes = [
        'postId'
    ];

    public function authorize (): bool {
        $post = Post::find($this->route('postId'));
        if (!$post) {
            return false;
        }

        return !$post->voted($this->session()->get('username'));
    }
}