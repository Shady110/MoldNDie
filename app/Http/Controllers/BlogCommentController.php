<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCommentController extends Controller
{
    public function index()
    {
        return BlogComment::all();
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'post_id' => 'required|exists:blog_posts,post_id',
        'user_id' => 'required|exists:users,user_id',
        'content' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Create the comment
    $comment = BlogComment::create($validator->validated());

    // Increment the comments_counter for the blog post
    BlogPost::where('post_id', $comment->post_id)->increment('comments_count');

    return response()->json($comment, 201);
}


    public function show($postId)
{
    // Retrieve all comments for the given post_id with user information
    $comments = BlogComment::with('user')->where('post_id', $postId)->get();

    // Transform the comments to include the username
    $formattedComments = $comments->map(function ($comment) {
        return [
            'comment_id' => $comment->id, // or whatever your comment primary key is
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'username' => $comment->user->username, // Assuming the User model has a 'username' field
            'content' => $comment->content,
            'created_at' => $comment->created_at, // Include any other fields you want
        ];
    });

    return response()->json($formattedComments);
}


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $comment = BlogComment::findOrFail($id);
        $comment->update($validator->validated());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->delete();
        return response()->json(null, 204);
    }
}
