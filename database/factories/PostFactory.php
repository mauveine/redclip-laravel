<?php


namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition () {
        $createdAt = $this->faker->dateTimeBetween('-10 days', 'now')->format('Y-m-d H:i:s');
        return [
            'username' => session()->get('username') ?? $this->faker->unique()->userName . $this->faker->unique()->uuid,
            'title' => $this->faker->realText(50),
            'content' => $this->faker->realText(200),
            'content_type' => 'text',
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ];
    }

    public function withImg () {
        return $this->state(function (array $attributes) {
            return [
                'content' => $this->faker->imageUrl(),
                'content_type' => 'img'
            ];
        });
    }

    public function withVideo () {
        return $this->state(function (array $attributes) {
            return [
                'content' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                'content_type' => 'video'
            ];
        });
    }

    public function withUsername ($username) {
        return $this->state(function (array $attributes) use ($username){
            return [
                'username' => $username
            ];
        });
    }

    public function withPredefinedUsername () {

        return $this->state(function (array $attributes) {
            return [
                'username' => 'testusersession1234'
            ];
        });
    }
}