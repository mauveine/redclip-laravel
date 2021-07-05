<?php

namespace Tests\Feature;

use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostActionsTest extends TestCase
{
    use DatabaseTransactions;

    protected $faker;
    protected $userSession;

    protected function setUp (): void {
        parent::setUp();
        $this->faker = Factory::create();
        $this->userSession = 'testusersession1234';
    }

    public function test_create_post () {
        $fakerUserSession = $this->faker->unique()->userName . $this->faker->unique()->uuid;
        $title = $this->faker->text(55);
        $content = $this->faker->imageUrl();
        $contentType = 'img';

        $response = $this->withSession(['username' => $fakerUserSession])
            ->json('POST', '/api/posts', [
            'title' => $title,
            'content' => $content,
            'content_type' => $contentType,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
            'data' => [
                'title' => $title,
                'content' => $content,
                'content_type' => $contentType,
                'username' => $fakerUserSession
            ],
            'username' => $fakerUserSession
        ]);
    }

    public function test_fail_duplicate_create_post () {
        $title = $this->faker->text(55);
        $content = $this->faker->imageUrl();
        $contentType = 'img';

        $this->json('POST', '/api/posts', [
            'title' => $title,
            'content' => $content,
            'content_type' => $contentType
        ]);

        $response = $this->json('POST', '/api/posts', [
            'title' => $title,
            'content' => $content,
            'content_type' => $contentType
        ]);

        $response->assertStatus(403);
    }

    public function test_simple_post_collection () {
        $response = $this->json('GET', '/api/posts');
        $data = $response->json('total');
        $this->assertGreaterThan(0, $data);
        $response->assertJsonStructure([
            'data' => [
                [
                    'my_vote_count',
                    'votes_count',
                    'username',
                    'title'
                ]
            ]
        ]);
    }


    public function test_get_post () {
        $post = Post::all()->random(1)->first();
        $response = $this->withSession(['username' => $this->userSession])
            ->json('GET', sprintf('/api/posts/%s', $post->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'username',
                    'my_vote_count',
                    'votes_count'
                ]
            ]);
    }

    public function test_update_post () {
        $post = Post::where('username', '=', $this->userSession)->first();

        $title = $this->faker->realText(50);
        $response = $this->withSession(['username' => $this->userSession])
            ->json('PATCH', sprintf('/api/posts/%s', $post->id), [
                'title' => $title
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => $title
                ]
            ]);
    }

    public function test_fail_update_not_own_post () {
        $post = Post::where('username', '!=', $this->userSession)->first();

        $title = $this->faker->realText(50);
        $response = $this->withSession(['username' => $this->userSession])
            ->json('PATCH', sprintf('/api/posts/%s', $post->id), [
                'title' => $title
            ]);
        $response->assertStatus(403);
    }

    public function test_delete_own_post () {
        $post = Post::where('username', '=', $this->userSession)->first();

        $response = $this->withSession(['username' => $this->userSession])
            ->json('DELETE', sprintf('/api/posts/%s', $post->id));
        $response->assertStatus(204);
    }

    public function test_fail_delete_not_own_post () {
        $post = Post::where('username', '!=', $this->userSession)->first();

        $response = $this->withSession(['username' => $this->userSession])
            ->json('DELETE', sprintf('/api/posts/%s', $post->id));
        $response->assertStatus(403);
    }

    public function test_vote_post () {
        $sessionName = $this->userSession;
        $post = Post::withCount(['myVote' => function ($query) use ($sessionName) {
            $query->where('username', '=', $sessionName);
        }])->where('username', '!=', $this->userSession)
            ->having('my_vote_count', '=', 0)
            ->get()->random(1)->first();
        $response = $this->withSession(['username' => $this->userSession])
            ->json('POST', sprintf('/api/posts/%s/vote', $post->id));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'my_vote_count' => 1
                ]
            ]);
    }

    public function test_fail_vote_post_twice () {
        $sessionName = $this->userSession;
        $post = Post::withCount(['myVote' => function ($query) use ($sessionName) {
            $query->where('username', '=', $sessionName);
        }])->where('username', '!=', $this->userSession)
            ->get()->random(1)->first();
        $this->withSession(['username' => $this->userSession])
            ->json('POST', sprintf('/api/posts/%s/vote', $post->id));

        $response = $this->withSession(['username' => $this->userSession])
            ->json('POST', sprintf('/api/posts/%s/vote', $post->id));
        $response->assertStatus(403);
    }
}
