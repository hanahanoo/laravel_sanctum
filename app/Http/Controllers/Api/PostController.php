<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::latest()->get();
        $res   = [
            'success' => true,
            'data'    => $posts,
            'message' => 'List posts',
        ];

        return response()->json($res, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:posts',
            'content' => 'required|string|max:255',
            'status' => 'required',
            'foto' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post          = new Post;
        $post->title   = $request->title;
        $post->slug    = Str::slug($request->title, '-');
        $post->content = $request->content;
        $post->status  = $request->status;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('posts', 'public');
            $post->foto = $path;
        }

        $post->save();

        $res = [
            'success' => true,
            'data'    => $post,
            'message' => 'Store Post'
        ];

        return response()->json($res, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Show Post Detail',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:posts,id,'.$id,
            'content' => 'required|string|max:255',
            'status' => 'required',
            'foto' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post          = Post::find($id);
        $post->title   = $request->title;
        $post->slug    = Str::slug($request->title, '-');
        $post->content = $request->content;
        $post->status  = $request->status;

        if ($request->hasFile('foto')) {
            if ($post->foto && Storage::disk('public', $post->foto)){
                Storage::disk('public')->delete($post->foto);
            }
            // upload foto baru
            $path       = $request->file('foto')->store('posts', 'public');
            $post->foto = $path;
        }

        $post->save();

        $res = [
            'success' => true,
            'data'    => $post,
            'message' => 'Update Post'
        ];

        return response()->json($res, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        // delete image di storage
        if ($post->foto && Storage::disk('public')->exists($post->foto)) {
            Storage::disk('public')->delete($post->foto);
        }

        $post->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
}