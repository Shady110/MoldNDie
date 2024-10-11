<?php

namespace App\Http\Controllers;

use App\Models\MoldLike;
use App\Models\Mold;
use Illuminate\Http\Request;

class MoldLikeController extends Controller
{
    // Like a mold
    public function likeMold(Request $request, $moldId)
    {
        $userId = $request->user()->user_id;

        // Check if the user already liked the mold
        $likeExists = MoldLike::where('user_id', $userId)->where('mold_id', $moldId)->exists();

        if ($likeExists) {
            return response()->json(['message' => 'Already liked this mold'], 400);
        }

        // Create a like
        MoldLike::create([
            'user_id' => $userId,
            'mold_id' => $moldId
        ]);

        // Increment the like count on the mold
        Mold::where('mold_id', $moldId)->increment('likes_count');

        return response()->json(['message' => 'Mold liked successfully'], 200);
    }

    // Unlike a mold
    public function unlikeMold(Request $request, $moldId)
    {
        $userId = $request->user()->user_id;

        // Check if the user liked the mold
        $like = MoldLike::where('user_id', $userId)->where('mold_id', $moldId)->first();

        if (!$like) {
            return response()->json(['message' => 'You haven\'t liked this mold'], 400);
        }

        // Delete the like
        $like->delete();

        // Decrement the like count on the mold
        Mold::where('mold_id', $moldId)->decrement('likes_count');

        return response()->json(['message' => 'Mold unliked successfully'], 200);
    }

    // Check if the user liked a mold
    public function hasLiked(Request $request, $moldId)
    {
        $userId = $request->user()->user_id;

        // Check if the like exists
        $hasLiked = MoldLike::where('user_id', $userId)->where('mold_id', $moldId)->exists();

        return response()->json(['hasLiked' => $hasLiked]);
    }
}
