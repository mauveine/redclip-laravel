<?php


namespace App\Http\Requests\Post;


use App\Models\Post;

class VotePostRequest extends BasePostRequest
{
    protected $method = 'POST';

    protected $requiredAttributes = [
        'post_id'
    ];

    public function authorize (): bool {
        $postId = $this->route('postId');
        $post = Post::find($postId);
        if (!$post) {
            return false;
        }
        $voted = $post->voted($this->session()->get('username'));
        return !$voted;
    }
}