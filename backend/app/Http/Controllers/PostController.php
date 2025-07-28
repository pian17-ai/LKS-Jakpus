<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

use function Laravel\Prompts\search;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post = $request->user()->posts()->create($fields);

        return response()->json([
            'message' => 'Post created'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // check post
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Post not found'
            ], 404);
        }

        return ['post' => $post];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);

        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post->update($fields);

        return response()->json([
            'status' => 200,
            'message' => 'Post modifed',
            'post' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {   
        Gate::authorize('modify', $post);

        $post->delete();

        return response()->json([
            'status' => 204,
        ], 204);
    }
}
