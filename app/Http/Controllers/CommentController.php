<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Mold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::all();
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,user_id',
        'mold_id' => 'required|exists:molds,mold_id',
        'content' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Create the comment
    $comment = Comment::create($validator->validated());

    // Increment the comments_counter for the blog post
    Mold::where('mold_id', $comment->mold_id)->increment('comments_count');

    return response()->json($comment, 201);
}

    public function show($moldId)
{
    // Retrieve all comments for the given post_id with user information
    $comments = Comment::with('user')->where('mold_id', $moldId)->get();

    // Transform the comments to include the username
    $formattedComments = $comments->map(function ($comment) {
        return [
            'comment_id' => $comment->id, // or whatever your comment primary key is
            'mold_id' => $comment->post_id,
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

        $comment = Comment::findOrFail($id);
        $comment->update($validator->validated());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(null, 204);
    }
}
