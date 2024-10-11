<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    public function index()
    {
        return Ad::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image file
            'link' => 'nullable|string|max:255',
            'status' => 'required|string|max:255|in:active,inactive', // Assuming 'status' can be 'active' or 'inactive'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validatedData = $validator->validated();

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/ads'), $imageName); // Save to 'images/ads' directory
            $imagePath = 'images/ads/' . $imageName;
        }

        // Add image path to the validated data
        $validatedData['image_path'] = $imagePath;

        // Create a new ad with the validated data
        $ad = Ad::create($validatedData);
        return response()->json($ad, 201);
    }

    public function show($id)
    {
        $ad = Ad::findOrFail($id);
        return response()->json($ad);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image file
            'link' => 'nullable|string|max:255',
            'status' => 'sometimes|string|max:255|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ad = Ad::findOrFail($id);
        $validatedData = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/ads'), $imageName);
            $validatedData['image_path'] = 'images/ads/' . $imageName;
        }

        // Update the ad with validated data
        $ad->update($validatedData);
        return response()->json($ad);
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();
        return response()->json(null, 204);
    }
}
