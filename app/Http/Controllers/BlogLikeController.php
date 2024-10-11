<?php

namespace App\Http\Controllers;

use App\Models\BlogLike;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogLikeController extends Controller
{
    // Like a post
    public function likePost(Request $request, $postId)
    {
        $userId = $request->user()->user_id;

        // Check if the user already liked the post
        $likeExists = BlogLike::where('user_id', $userId)->where('post_id', $postId)->exists();

        if ($likeExists) {
            return response()->json(['message' => 'Already liked this post'], 400);
        }

        // Create a like
        BlogLike::create([
            'user_id' => $userId,
            'post_id' => $postId
        ]);

        // Increment the like count on the post
        BlogPost::where('post_id', $postId)->increment('likes_count');

        return response()->json(['message' => 'Post liked successfully'], 200);
    }

    // Unlike a post
    public function unlikePost(Request $request, $postId)
    {
        $userId = $request->user()->user_id;

        // Check if the user liked the post
        $like = BlogLike::where('user_id', $userId)->where('post_id', $postId)->first();

        if (!$like) {
            return response()->json(['message' => 'You haven\'t liked this post'], 400);
        }

        // Delete the like
        $like->delete();

        // Decrement the like count on the post
        BlogPost::where('post_id', $postId)->decrement('likes_count');

        return response()->json(['message' => 'Post unliked successfully'], 200);
    }

    // Check if the user liked a post
    public function hasLiked(Request $request, $postId)
    {
        $userId = $request->user()->user_id;

        $hasLiked = BlogLike::where('user_id', $userId)->where('post_id', $postId)->exists();

        return response()->json(['hasLiked' => $hasLiked]);
    }
}
