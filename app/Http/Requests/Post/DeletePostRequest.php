<?php


namespace App\Http\Requests\Post;


use App\Models\Post;

class DeletePostRequest extends GetPostRequest
{
    protected $method = 'DELETE';

    public function authorize (): bool {
        $currentUser = $this->session()->get('username');
        $post = Post::find($this->route('postId'));
        if (!$post) {
            return false;
        }

        return $post->username === $currentUser;
    }
}