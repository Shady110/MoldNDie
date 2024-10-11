<?php

namespace App\Http\Controllers;

use App\Models\Mold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MoldController extends Controller
{
    public function index(Request $request)
    {
        // Start with the base query
        $query = Mold::query();

        // Search by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter by category_id if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by application if provided
        if ($request->has('application')) {
            $query->where('application', $request->application);
        }

        // Apply sorting styles
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'most downloaded':
                    $query->orderBy('downloads', 'desc');
                    break;
                case 'most liked':
                    $query->orderBy('likes', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Paginate the results (9 per page)
        $molds = $query->paginate(9);

        // Return paginated results
        return response()->json($molds);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|integer',
        'application' => 'required|string',
        'file' => 'required|file|mimes:zip,rar|max:20480', // Only zip or rar files allowed, max 20MB
        'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Thumbnail max 2MB
        'downloads' => 'integer|nullable',
        'likes' => 'integer|nullable',
        'comments_count' => 'integer|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $validatedData = $validator->validated();

    // Handle file upload
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('molds', 'public');
        $validatedData['file_path'] = $filePath; // Save the file path to the database
    }

    // Handle thumbnail upload
    if ($request->hasFile('thumbnail')) {
        $image = $request->file('thumbnail');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images/thumbnails'), $imageName);
        $validatedData['thumbnail'] = 'images/thumbnails/' . $imageName;
    }

    $mold = Mold::create($validatedData);
    return response()->json($mold, 201);
}


    public function show($id)
    {
        $mold = Mold::findOrFail($id);
        return response()->json($mold);
    }

    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'category_id' => 'sometimes|integer',
        'application' => 'sometimes|string',
        'file' => 'sometimes|file|mimes:zip,rar|max:20480', // Optional file validation
        'thumbnail' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional thumbnail validation
        'downloads' => 'integer|nullable',
        'likes' => 'integer|nullable',
        'comments_count' => 'integer|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $mold = Mold::findOrFail($id);
    $validatedData = $validator->validated();

    // Handle file upload if provided
    if ($request->hasFile('file')) {
        // Delete old file if it exists
        if ($mold->file_path) {
            Storage::disk('public')->delete($mold->file_path);
        }

        // Store the new file
        $filePath = $request->file('file')->store('molds', 'public');
        $validatedData['file_path'] = $filePath;
    }

    // Handle thumbnail upload if provided
    if ($request->hasFile('thumbnail')) {
        // Delete old thumbnail if it exists
        if ($mold->thumbnail && file_exists(public_path($mold->thumbnail))) {
            unlink(public_path($mold->thumbnail));
        }

        // Store the new thumbnail
        $image = $request->file('thumbnail');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images/thumbnails'), $imageName);
        $validatedData['thumbnail'] = 'images/thumbnails/' . $imageName;
    }

    // Update the mold with the validated data
    $mold->update($validatedData);

    return response()->json($mold);
}


    public function destroy($id)
    {
        $mold = Mold::findOrFail($id);
        
        // Delete the associated file from storage
        if ($mold->file_path) {
            Storage::disk('public')->delete($mold->file_path);
        }

        // Delete the associated thumbnail from storage
        if ($mold->thumbnail_path) {
            Storage::disk('public')->delete($mold->thumbnail_path);
        }

        $mold->delete();
        return response()->json(null, 204);
    }

public function incrementDownload($id)
{
    $mold = Mold::findOrFail($id);
    
    // Increment the downloads count
    $mold->increment('downloads');

    return response()->json(['message' => 'Download count incremented'], 200);
}

public function download($id)
{
    $mold = Mold::findOrFail($id);

    // Assuming the file_path contains the path to the file
    $file_path = storage_path('app/public/' . $mold->file_path);

    if (!file_exists($file_path)) {
        return response()->json(['message' => 'File not found.'], 404);
    }

    // Return the file as a response
    return response()->download($file_path);
}

}
