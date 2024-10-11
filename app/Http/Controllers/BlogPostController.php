<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    // Fetch all blog posts or search by title
   public function index(Request $request)
{
    $query = BlogPost::query();

    // Search by title
    if ($request->has('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    // Filter by category_id
    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Filter by tag_id
    if ($request->has('tag_id')) {
        $tagId = $request->tag_id;
        
        // Add a condition to retrieve posts with the given tag
        $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.tag_id', $tagId);
        });
    }

    // Paginate the results, 6 posts per page
    $posts = $query->with('tags')->paginate(6); // Include tags in the response

    return response()->json($posts, 200);
}


    // Store a new blog post with thumbnail and tags
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'introduction' => 'required|string',
            'content' => 'required|string',
            'user_id' => 'nullable|exists:users,user_id', // Validate that the user exists
            'category_id' => 'required|exists:blog_categories,category_id', // Validate category exists
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the thumbnail image
            'tags' => 'array', // Validate that tags is an array
            'tags.*' => 'exists:tags,tag_id' // Validate that each tag exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validatedData = $validator->validated();

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/thumbnails'), $imageName);
            $thumbnailPath = 'images/thumbnails/' . $imageName;
        }

        // Add thumbnail path to the validated data
        $validatedData['thumbnail'] = $thumbnailPath;

        // Create new blog post
        $post = BlogPost::create($validatedData);

        // Attach tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags); // Sync tags
        }

        return response()->json($post->load('tags'), 201); // Include tags in the response
    }

    // Show a single blog post by ID, including its tags
    public function show($id)
    {
        $post = BlogPost::with('tags')->findOrFail($id); // Load tags
        return response()->json($post, 200);
    }

    // Update an existing blog post with a new thumbnail and tags
    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'introduction' => 'sometimes|string',
            'content' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,user_id', // Ensure the user exists if provided
            'category_id' => 'sometimes|exists:blog_categories,category_id', // Ensure the category exists if provided
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate new thumbnail
            'tags' => 'array', // Validate tags as an array
            'tags.*' => 'exists:tags,tag_id' // Ensure each tag exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validatedData = $validator->validated();

        // Handle new thumbnail upload if provided
        if ($request->hasFile('thumbnail')) {
            // Delete the old thumbnail if exists
            if ($post->thumbnail) {
                unlink(public_path($post->thumbnail));
            }

            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/thumbnails'), $imageName);
            $validatedData['thumbnail'] = 'images/thumbnails/' . $imageName;
        }

        // Update the blog post with validated data
        $post->update($validatedData);

        // Sync tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags); // Sync tags
        }

        return response()->json($post->load('tags'), 200); // Include tags in the response
    }

    // Delete a blog post along with its tags
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // Delete the thumbnail if exists
        if ($post->thumbnail) {
            unlink(public_path($post->thumbnail));
        }

        // Detach associated tags
        $post->tags()->detach();

        // Delete the blog post
        $post->delete();

        return response()->json(null, 204);
    }
}
