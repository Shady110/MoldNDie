<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogTagController extends Controller
{
    // Fetch all tags associated with a specific post
    public function index(Request $request)
    {
        // Validate the request to ensure post_id is provided
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,post_id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Fetch the blog post with associated tags
        $post = BlogPost::with('tags')->findOrFail($request->post_id);
        return response()->json($post->tags, 200); // Return only the tags
    }

    // Attach a tag to a post
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,post_id', // Ensure post exists
            'tag_id' => 'required|exists:tags,tag_id', // Ensure tag exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = BlogPost::findOrFail($request->post_id);

        // Attach the tag to the post
        $post->tags()->attach($request->tag_id);

        return response()->json($post->load('tags'), 201); // Return post with updated tags
    }

    // Detach a tag from a post
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,post_id', // Ensure post exists
            'tag_id' => 'required|exists:tags,tag_id', // Ensure tag exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = BlogPost::findOrFail($request->post_id);

        // Detach the tag from the post
        $post->tags()->detach($request->tag_id);

        return response()->json($post->load('tags'), 200); // Return post with updated tags
    }
}
