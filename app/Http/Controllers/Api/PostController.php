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
        if (auth()->user()->is_admin) {
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
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to create post'
        ]);
    }

    public function update(EditPostRequest $request, Post $post)
    {
        if ($post->user_id === auth()->user()->id || auth()->user()->is_admin) {
            try {
                $post->title = $request->title;
                $post->img = $request->img;
                $post->txt = $request->txt;
                $post->updated_by = auth()->user()->id;
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
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to edit this post'
        ]);
    }

    public function destroy(Post $post)
    {

        if ($post->user_id === auth()->user()->id || auth()->user()->is_admin) {
            try {
                $post->updated_by = auth()->user()->id;
                $post->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Post deleted'
                ]);
            } catch (Exception $e) {
                return response()->json($e);
            }
        }
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to delete this post'
        ]);
    }
}
