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
            $query = Post::query();
            $perPage = 3;
            $page = $request->input('page',1);
            $title = $request->input('title');
            $txt = $request->input('txt');
            if($title || $txt){
                $query::whereRaw("title LIKE '%$title%' and txt LIKE '%$txt%'");
            }
            $count = $query->count();
            $result = $query->offset(($page-1)*$perPage)->limit($perPage)->get();

            return response()->json([
                'status'=>200,
                'message'=>'Posts found',
                'current_page'=>$page,
                'last_page'=>ceil($count/$perPage),
                'data'=>$result
            ]);
        } catch (Exception $e){
            return response()->json($e);
        }
    }
    
    public function get(Post $post)
    {
        try {
            return response()->json([
                'status'=>200,
                'message'=>'Post found',
                'data'=>$post
            ]);
        } catch (Exception $e){
            return response()->json($e);
        }
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $post = new Post();
            $post->title = $request->title;
            $post->img = $request->img;
            $post->txt = $request->txt;
            $post->save();
            
            return response()->json([
                'status'=>201,
                'message'=>'Post created',
                'data'=>$post
            ]);
        } catch (Exception $e){
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
                'status'=>200,
                'message'=>'Post updated',
                'data'=>$post
            ]);
        } catch (Exception $e){
            return response()->json($e);
        }
    }

    public function delete(Request $request, Post $post)
    {
        try {
            $post->delete();

            return response()->json([
                'status'=>200,
                'message'=>'Post deleted'
            ]);
            
        } catch (Exception $e){
            return response()->json($e);
        }
    }
}
