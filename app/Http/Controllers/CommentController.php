<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * 특정 게시글의 댓글 목록
     * - GET /api/posts/{post}/comments
     */
    public function index(Post $post)
    {
        $perPage = request('per_page', 10);

        $comments = $post->comments()
            ->latest('created_at')
            ->paginate($perPage);

        return response()->json($comments);
    }

    /**
     * 댓글 생성
     * - POST /api/posts/{post}/comments
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = $post->comments()->create($validated);

        return response()->json($comment, Response::HTTP_CREATED);
    }

    /**
     * 댓글 상세
     * - GET /api/posts/{post}/comments/{comment}
     */
    public function show(Post $post, Comment $comment)
    {
        // 스코프 바인딩 안 쓸때
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        return response()->json($comment);
    }

    /**
     * 댓글 수정
     * - PUT /api/posts/{post}/comments/{comment}
     * - PATCH /api/posts/{post}/comments/{comment}
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        // 스코프 바인딩 안 쓸때
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $isPut = strtoupper($request->method()) === 'PUT';
        $validated = $request->validate([
            'content' => [$isPut ? 'required' : 'sometimes', 'string', 'max:255'],
        ]);

        $comment->fill($validated)->save();

        return response()->json($comment);
    }

    /**
     * 댓글 삭제
     * - DELETE /api/posts/{post}/comments/{comment}
     */
    public function destroy(Post $post, Comment $comment)
    {
        // 스코프 바인딩 안 쓸때
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comment->delete();

        return response()->noContent();
    }
}
