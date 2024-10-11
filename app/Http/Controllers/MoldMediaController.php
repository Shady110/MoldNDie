<?php

namespace App\Http\Controllers;

use App\Models\MoldMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoldMediaController extends Controller
{
    public function index()
    {
        return MoldMedia::all();
    }

   public function store(Request $request)
{
    // Define validation rules based on media type
    $validator = Validator::make($request->all(), [
        'mold_id' => 'required|exists:molds,mold_id',
        'media_type' => 'required|in:image,video',
        'images.*' => 'required_if:media_type,image|file|mimes:jpeg,png,jpg,gif|max:2048', // Images max 2MB
        'videos' => 'required_if:media_type,video|file|mimes:mp4,mov,avi,flv|max:20000', // Videos max 20MB
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
            $imageName = time() . '_' . uniqid() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $storedMediaPaths[] = $imagePath;

            // Save the path in the database
            MoldMedia::create([
                'mold_id' => $validatedData['mold_id'],
                'media_path' => $imagePath,
                'media_type' => $mediaType,
            ]);
        }
    } 
    // Handle video uploads
    elseif ($mediaType === 'video' && $request->hasFile('videos')) {
        $video = $request->file('videos');
        $videoName = time() . '_' . uniqid() . '.' . $video->extension();
        $video->move(public_path('videos'), $videoName);
        $videoPath = 'videos/' . $videoName;
        $storedMediaPaths[] = $videoPath;

        // Save the path in the database
        MoldMedia::create([
            'mold_id' => $validatedData['mold_id'],
            'media_path' => $videoPath,
            'media_type' => $mediaType,
        ]);
    }

    return response()->json(['media_paths' => $storedMediaPaths], 201);
}



    public function show($mold_id)
    {
        // Fetch all media entries for the given mold_id
    $media = MoldMedia::where('mold_id', $mold_id)->get();

    // Check if media exists for the given mold_id
    if ($media->isEmpty()) {
        return response()->json(['message' => 'No media found for this mold ID'], 404);
    }

    // Return the media as JSON response
    return response()->json($media, 200);
    }

    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'mold_id' => 'sometimes|exists:molds,mold_id',
        'media_type' => 'sometimes|in:image,video',
        'images.*' => 'required_if:media_type,image|file|mimes:jpeg,png,jpg,gif|max:2048', // Images max 2MB
        'videos' => 'required_if:media_type,video|file|mimes:mp4,mov,avi,flv|max:20000', // Videos max 20MB
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $media = MoldMedia::findOrFail($id);
    $validatedData = $validator->validated();
    $mediaType = $validatedData['media_type'] ?? $media->media_type; // Keep original media_type if not provided
    $storedMediaPaths = json_decode($media->media_path, true) ?: [];

    // Handle image uploads
    if ($mediaType === 'image' && $request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imageName = time() . '_' . uniqid() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            $storedMediaPaths[] = $imagePath;
        }
    } 
    // Handle video uploads
    elseif ($mediaType === 'video' && $request->hasFile('videos')) {
        $video = $request->file('videos');
        $videoName = time() . '_' . uniqid() . '.' . $video->extension();
        $video->move(public_path('videos'), $videoName);
        $videoPath = 'videos/' . $videoName;
        $storedMediaPaths[] = $videoPath;
    }

    // Update the database with the new media paths
    $media->update([
        'mold_id' => $validatedData['mold_id'] ?? $media->mold_id, // Update only if provided
        'media_path' => json_encode($storedMediaPaths),
        'media_type' => $mediaType,
    ]);

    return response()->json($media);
}


    public function destroy($id)
    {
        $media = MoldMedia::findOrFail($id);
        $media->delete();
        return response()->json(null, 204);
    }
}
