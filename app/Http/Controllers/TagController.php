<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    // Fetch all tags
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags, 200);
    }

    // Store a new tag
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name', // Ensure the tag name is unique
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json($tag, 201);
    }

    // Show a specific tag by ID
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag, 200);
    }

    // Update an existing tag
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name,' . $id, // Ensure the tag name is unique except for the current tag
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tag->update([
            'name' => $request->name,
        ]);

        return response()->json($tag, 200);
    }

    // Delete a tag
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        // Detach the tag from all blog posts before deletion
        $tag->blogPosts()->detach();

        $tag->delete();
        return response()->json(null, 204);
    }

    // Associate a tag with a blog post
    public function attachToPost(Request $request, $tagId)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,post_id', // Validate that the post exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tag = Tag::findOrFail($tagId);
        $tag->blogPosts()->attach($request->post_id); // Attach tag to blog post

        return response()->json(['message' => 'Tag attached to blog post'], 200);
    }

    // Detach a tag from a blog post
    public function detachFromPost(Request $request, $tagId)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,post_id', // Validate that the post exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tag = Tag::findOrFail($tagId);
        $tag->blogPosts()->detach($request->post_id); // Detach tag from blog post

        return response()->json(['message' => 'Tag detached from blog post'], 200);
    }
    
    // Get all blog posts associated with a specific tag
    public function getPostsByTag($tagId)
    {
        // Find the tag by its ID
        $tag = Tag::findOrFail($tagId);

        // Retrieve all blog posts associated with this tag
        $posts = $tag->blogPosts()->with('tags')->paginate(6);

        return response()->json($posts, 200);
    }
}
