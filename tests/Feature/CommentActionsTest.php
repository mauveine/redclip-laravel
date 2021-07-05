<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentActionsTest extends TestCase
{
    use DatabaseTransactions;

    protected $faker;
    protected $userSession;

    protected function setUp (): void {
        parent::setUp();
        $this->faker = Factory::create();
        $this->userSession = 'testusersession1234';
    }

    public function test_create_comment () {
        $fakerUserSession = $this->faker->unique()->userName . $this->faker->unique()->uuid;
        $reply = $this->faker->realText(25);
        $post = Post::where('username', '!=', $fakerUserSession)
            ->first();
        $response = $this->withSession(['username' => $fakerUserSession])
            ->json('POST', sprintf('/api/posts/%s/comments', $post->id), [
                'reply' => $reply
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => $fakerUserSession,
                    'reply' => $reply
                ],
                'username' => $fakerUserSession
            ]);
    }

    public function test_fail_duplicate_create_comment () {
        $fakerUserSession = $this->faker->unique()->userName . $this->faker->unique()->uuid;
        $reply = $this->faker->realText(25);
        $post = Post::where('username', '!=', $fakerUserSession)
            ->get()->random(1)
            ->first();

        $this->withSession(['username' => $fakerUserSession])
            ->json('POST', sprintf('/api/posts/%s/comments', $post->id), [
                'reply' => $reply
            ]);

        $response = $this->withSession(['username' => $fakerUserSession])
            ->json('POST', sprintf('/api/posts/%s/comments', $post->id), [
                'reply' => $reply
            ]);

        $response->assertStatus(403);
    }

    public function test_update_own_comment () {
        $comment = Comment::with(['post'])->where('username', '=', $this->userSession)
            ->get()->random(1)->first();
        $reply = $this->faker->realTextBetween(5, 100);
        $response = $this->withSession(['username' => $this->userSession])
            ->json('PATCH', sprintf('/api/posts/%s/comments/%s', $comment->post->id, $comment->id), [
                'reply' => $reply
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'reply' => $reply,
                    'username' => $this->userSession
                ],
                'username' => $this->userSession
            ]);
    }

    public function test_fail_update_not_own_comment () {
        $comment = Comment::with(['post'])->where('username', '!=', $this->userSession)
            ->get()->random(1)->first();
        $reply = $this->faker->realTextBetween(5, 100);
        $response = $this->withSession(['username' => $this->userSession])
            ->json('PATCH', sprintf('/api/posts/%s/comments/%s', $comment->post->id, $comment->id), [
                'reply' => $reply
            ]);

        $response->assertStatus(403);
    }

    public function test_get_comment_with_replies () {
        /** @var Comment $comment */
        $comment = Comment::with(['replies'])->whereHas('replies')->get()->random(1)->first();
        $response = $this->withSession(['username' => $this->userSession])
            ->json('GET', sprintf('/api/posts/%s/comments/%s', $comment->post->id, $comment->id));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $comment->id,
                    'reply' => $comment->reply,
                    'username' => $comment->username,
                    'post_id' => $comment->post->id
                ]
            ]);
        $response->assertJsonStructure([
            'data' => [
                'my_vote_count',
                'votes_count',
                'replies' => [
                    [
                        'id',
                        'reply',
                        'username',
                        'votes_count',
                        'my_vote_count'
                    ]
                ]
            ]
        ]);
    }

    public function test_delete_own_comment () {
        $comment = Comment::with(['post'])->where('username', '=', $this->userSession)
            ->get()->random(1)->first();
        $response = $this->withSession(['username' => $this->userSession])
            ->json('DELETE', sprintf('/api/posts/%s/comments/%s', $comment->post->id, $comment->id));

        $response->assertStatus(204);
    }

    public function test_delete_not_own_comment () {
        $comment = Comment::with(['post'])->where('username', '!=', $this->userSession)
            ->get()->random(1)->first();
        $response = $this->withSession(['username' => $this->userSession])
            ->json('DELETE', sprintf('/api/posts/%s/comments/%s', $comment->post->id, $comment->id));

        $response->assertStatus(403);
    }

    public function test_vote_comment () {
        $comment = Comment::with(['post'])->withCount(['myVote'])
            ->having('my_vote_count', '=', 0)
            ->where('username', '!=', $this->userSession)
            ->get()->random(1)->first();
        $response = $this->withSession(['username' => $this->userSession])
            ->json('POST', sprintf('/api/posts/%s/comments/%s/vote', $comment->post->id, $comment->id));
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $comment->id,
                    'my_vote_count' => 1
                ]
            ]);
    }

    public function test_fail_vote_comment_twice () {
        $comment = Comment::with(['post'])->withCount(['myVote'])
            ->having('my_vote_count', '=', 0)
            ->where('username', '!=', $this->userSession)
            ->get()->random(1)->first();
        $this->withSession(['username' => $this->userSession])
            ->json('POST', sprintf('/api/posts/%s/comments/%s/vote', $comment->post->id, $comment->id));

        $response = $this->withSession(['username' => $this->userSession])
            ->json('POST', sprintf('/api/posts/%s/comments/%s/vote', $comment->post->id, $comment->id));

        $response->assertStatus(403);
    }
}
