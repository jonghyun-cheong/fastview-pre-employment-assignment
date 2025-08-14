<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * 목록 조회 (페이지네이션)
     * - GET /api/posts?per_page=10&page=1
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $posts = Post::query()
            ->latest('created_at')
            ->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * 생성
     *
     * - POST /api/posts
     * body: {"title": "...", "content": "...", "author": "..."}
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:100',
        ]);

        $post = Post::create($validated);

        return response()->json($post, Response::HTTP_CREATED);
    }

    /**
     * 상세 조회
     * - GET /api/posts/{id}
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * 수정
     * - PUT /api/posts/{id} (전체 수정)
     * - POST /api/posts/{id} (부분 수정)
     */
    public function update(Request $request, Post $post)
    {
        // 메소드 구분
        $isPut = strtoupper($request->method()) === 'PUT';

        $rules = [
            'title' => [$isPut ? 'required' : 'sometimes', 'string', 'max:255'],
            'content' => [$isPut ? 'required' : 'sometimes', 'string'],
            'author' => [$isPut ? 'required' : 'sometimes', 'string', 'max:100']
        ];

        $validated = $request->validate($rules);

        $post->fill($validated)->save();

        return response()->json($post);
    }

    /**
     * 삭제
     * - DELETE /api/posts/{id}
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
