<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\GetCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\VoteCommentRequest;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CommentController extends Base
{
    public function store (CreateCommentRequest $request) {
        $validate = $request->validated();
        try {
            $comment = Comment::create($request->all());
            return $this->respond([
                'data' => $comment->loadCount(['votes', 'myVote'])->load('replies')->toArray()
            ]);
        } catch (QueryException $exception) {
            return $this->respond([
                'error' => 'Duplicate key'
            ], parent::FORBIDDEN);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);

        }
    }

    public function show (GetCommentRequest $request, $postId, $commentId) {
        $validate = $request->validated();
        try {
            $comment = Comment::withCount(['votes', 'myVote'])->with(['replies'])
                ->where('id', '=', (int)$commentId)
                ->where('post_id', '=', (int)$postId)
                ->first();
            return $this->respond([
                'data' => $comment->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }

    public function update (UpdateCommentRequest $request, $postId, $commentId) {
        $validated = $request->validated();
        try {
            $comment = Comment::withCount(['votes', 'myVote'])->with(['replies'])
                ->where('id', '=', (int)$commentId)
                ->where('post_id', '=', (int)$postId)
                ->first();
            $data = $request->all();
            unset($data['post_id']);
            unset($data['comment_id']);
            $comment->update($data);
            return $this->respond([
                'data' => $comment->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }

    public function destroy (DeleteCommentRequest $request, $postId, $commentId) {
        $validate = $request->validated();
        try {
            $comment = Comment::find($commentId);
            $comment->delete();
            return $this->respond([], self::SUCCESS_EMPTY);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }

    public function vote (VoteCommentRequest $request, $postId, $commentId) {
        $validate = $request->validated();
        try {
            $data = $request->all();
            unset($data['post_id']);
            $vote = Vote::create($data);
            $comment = Comment::withCount(['votes', 'myVote'])->with(['replies'])
                ->where('id', '=', (int)$commentId)
                ->first();
            return $this->respond([
                'data' => $comment->toArray()
            ]);
        } catch (QueryException $exception) {
            return $this->respond([
                'error' => 'Duplicate key'
            ], parent::FORBIDDEN);
        } catch (\Exception $e) {
            return $this->respond([
                'error' => $e->getMessage()
            ], parent::BAD_REQUEST);
        }
    }
}
