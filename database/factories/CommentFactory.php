<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $post = Post::all()->random(1)->first();
        return [
            'username' => $this->faker->unique()->userName . $this->faker->unique()->uuid,
            'reply' => $this->faker->realTextBetween(25, 150),
            'post_id' => $post->id
        ];
    }

    public function toComment ($uname = null) {
        return $this->state(function (array $attributes) use ($uname) {
            $username = $uname ?? $attributes['username'];
            $comment = Comment::with('post')
                ->whereHas('post', function ($query) use ($username) {
                    $query->where('username', '!=', $username);
                })
                ->where('username', '!=', $username)
                ->get()->random(1)->first();
            $return = [
                'post_id' => $comment->post->id,
                'comment_id' => $comment->id
            ];
            return $uname ? array_merge($return, ['username' => $uname]) : $return;
        });
    }

    public function differentPost ($uname = null) {
        return $this->state(function (array $attributes) use ($uname) {
            $username = $uname ?? $attributes['username'];
            $comment = Post::where('username', '!=', $username)
                ->get()->random(1)->first();
            $return = [
                'post_id' => $comment->id
            ];
            return $uname ? array_merge($return, ['username' => $uname]) : $return;
        });
    }
}
