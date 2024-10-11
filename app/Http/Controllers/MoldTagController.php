<?php

namespace App\Http\Controllers;

use App\Models\MoldTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoldTagController extends Controller
{
    public function index()
    {
        return MoldTag::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mold_id' => 'required|exists:molds,id',
            'tag' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tag = MoldTag::create($validator->validated());
        return response()->json($tag, 201);
    }

    public function show($id)
    {
        $tag = MoldTag::findOrFail($id);
        return response()->json($tag);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tag' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tag = MoldTag::findOrFail($id);
        $tag->update($validator->validated());
        return response()->json($tag);
    }

    public function destroy($id)
    {
        $tag = MoldTag::findOrFail($id);
        $tag->delete();
        return response()->json(null, 204);
    }
}
