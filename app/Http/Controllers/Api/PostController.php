<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditPostRequest;
use App\Http\Requests\CreatePostRequest;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            // $query = Post::query();
            $perPage = $request->input('perPage', 3);
            $page = $request->input('page', 1);
            $title = $request->input('title');
            $txt = $request->input('txt');
            $query = Post::where('title', 'LIKE', '%' . $title . '%')->where('txt', 'LIKE', '%' . $txt . '%');
            $count = $query->count();
            $perPage === 'all' ? $perPage = $count : '';
            $lastPage = ceil($count / $perPage);
            $page > $lastPage ? $page = $lastPage : '';
            $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            return response()->json([
                'status' => 200,
                'message' => 'Posts found',
                'current_page' => $page,
                'last_page' => $lastPage,
                'data' => $result
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function show(Post $post)
    {
        try {
            return response()->json([
                'status' => 200,
                'message' => 'Post found',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $post = new Post();
            $post->user_id = auth()->user()->id;
            $post->title = $request->title;
            $post->img = $request->img;
            $post->txt = $request->txt;
            $post->save();

            return response()->json([
                'status' => 201,
                'message' => 'Post created',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function update(EditPostRequest $request, Post $post)
    {
        try {
            $post->title = $request->title;
            $post->img = $request->img;
            $post->txt = $request->txt;
            $post->save();

            return response()->json([
                'status' => 200,
                'message' => 'Post updated',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Post deleted'
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
