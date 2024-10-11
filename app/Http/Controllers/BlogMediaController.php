<?php

namespace App\Http\Controllers;

use App\Models\BlogMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogMediaController extends Controller
{
    public function index()
    {
        return BlogMedia::all();
    }

    public function store(Request $request)
    {
        // Define validation rules based on media type
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:blog_posts,post_id',
            'media_type' => 'required|in:image,video',
            'images.*' => 'required_if:media_type,image|file|mimes:jpeg,png,jpg,gif|max:2048', // Images max 2MB
            'videos' => 'required_if:media_type,video|file|mimes:mp4,mov,avi,flv|max:20000', // Video max 20MB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validatedData = $validator->validated();
        $mediaType = $validatedData['media_type'];
        $storedMediaPaths = [];

        // Handle image uploads
        if ($mediaType === 'image' && $request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images'), $imageName);
                $imagePath = 'images/' . $imageName;
                $storedMediaPaths[] = $imagePath;

                // Save the path in the database
                BlogMedia::create([
                    'post_id' => $validatedData['post_id'],
                    'media_path' => $imagePath,
                    'media_type' => $mediaType,
                ]);
            }
        } 
        // Handle video uploads
        elseif ($mediaType === 'video' && $request->hasFile('videos')) {
            $video = $request->file('videos');
            $videoName = time() . '.' . $video->extension();
            $video->move(public_path('videos'), $videoName);
            $videoPath = 'videos/' . $videoName;
            $storedMediaPaths[] = $videoPath;

            // Save the path in the database
            BlogMedia::create([
                'post_id' => $validatedData['post_id'],
                'media_path' => $videoPath,
                'media_type' => $mediaType,
            ]);
        }

        return response()->json(['media_paths' => $storedMediaPaths], 201);
    }

    public function show($postId)
    {
        $media = BlogMedia::where('post_id', $postId)->get();

        if ($media->isEmpty()) {
            return response()->json(['message' => 'No media found for this post.'], 404);
        }

        return response()->json($media);
    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'media_path' => 'sometimes|string',
            'media_type' => 'sometimes|in:image,video',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $media = BlogMedia::findOrFail($id);
        $media->update($validator->validated());
        return response()->json($media);
    }

    public function destroy($id)
    {
        $media = BlogMedia::findOrFail($id);
        
        // Delete the file from storage
        unlink(public_path($media->media_path)); // Use unlink to delete the file
        
        $media->delete();
        return response()->json(null, 204);
    }
}
