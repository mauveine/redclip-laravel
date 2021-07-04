<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\DeletePostRequest;
use App\Http\Requests\Post\GetAllPostRequest;
use App\Http\Requests\Post\GetPostRequest;
use App\Http\Requests\Post\VotePostRequest;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PostController extends Base
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test (Request $request) {
        return $this->respond([]);
    }

    public function all(GetAllPostRequest $request)
    {
        $validated = $request->validated();
        try {
            $page = $request->input('page') ?? 1;
            $perPage = $request->input('perPage') ?? 10;
            $orderDirection = $request->input('order') ?? 'desc';
            $posts = Post::withCount(['votes', 'myVote'])
                ->orderBy('created_at', $orderDirection);

            $posts = $posts->paginate($perPage, ['*'], 'page', $page);
            return $this->respond(
                $posts->toArray()
            );
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage(),
            ], parent::BAD_REQUEST
            );
        }
    }


    public function create (CreatePostRequest $request) {
        $validate = $request->validated();
        try {
            $post = Post::create($request->all());
            return $this->respond([
                'data' => $post->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);

        }
    }

    public function show (GetPostRequest $request, $postId) {
        $validate = $request->validated();
        try {
            $post = Post::withCount(['votes', 'myVote'])->where('id', '=', (int)$postId);
            return $this->respond([
                'data' => $post->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }

    public function update (GetPostRequest $request, $postId) {
        $validate = $request->validated();
        try {
            $post = Post::withCount(['votes', 'myVote'])->where('id', '=', (int)$postId);
            $post->update($request->all());
            return $this->respond([
                'data' => $post->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }

    public function destroy (DeletePostRequest $request, $postId) {
        $validate = $request->validated();
        try {
            $post = Post::find($postId);
            $post->delete();
            return $this->respond([
                'data' => $post->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }

    public function vote (VotePostRequest $request, $postId) {
        $validate = $request->validated();
        try {
            Vote::create($request->all());
            $post = Post::withCount(['votes', 'myVote'])->where('id', '=', (int)$postId);
            return $this->respond([
                'data' => $post->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }
}
