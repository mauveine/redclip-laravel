<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName . $this->faker->unique()->uuid
        ];
    }

    public function votePosts ($uname = null) {
        return $this->state(function (array $attributes) use ($uname) {
            $username = $uname ?? $attributes['username'];
            session()->put('username', $username);
            $post = Post::withCount('myVote')
                ->having('my_vote_count', '=', 0)->get()->random(1)->first();

            $return = [
                'post_id' => $post->id
            ];
            return $uname ? array_merge($return, ['username' => $uname]) : $return;
        });
    }

    public function voteComments ($uname = null) {
        return $this->state(function (array $attributes) use ($uname) {
            $username = $uname ?? $attributes['username'];
            session()->put('username', $username);
            $comment = Comment::withCount('myVote')
                ->having('my_vote_count', '=', 0);

            $comment = $comment->get()->random(1)->first();
            $return = [
                'comment_id' => $comment->id
            ];
            return $uname ? array_merge($return, ['username' => $uname]) : $return;
        });
    }
}
