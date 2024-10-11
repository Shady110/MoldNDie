<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function index()
    {
        return UserProfile::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'bio' => 'sometimes|string|max:1000',
            'website' => 'sometimes|string|max:255|url',
            'location' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $profile = UserProfile::create($validator->validated());
        return response()->json($profile, 201);
    }

    public function show($id)
    {
        $profile = UserProfile::findOrFail($id);
        return response()->json($profile);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'sometimes|string|max:1000',
            'website' => 'sometimes|string|max:255|url',
            'location' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $profile = UserProfile::findOrFail($id);
        $profile->update($validator->validated());
        return response()->json($profile);
    }

    public function destroy($id)
    {
        $profile = UserProfile::findOrFail($id);
        $profile->delete();
        return response()->json(null, 204);
    }
}
