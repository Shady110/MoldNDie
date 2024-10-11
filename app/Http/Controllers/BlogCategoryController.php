<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return BlogCategory::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:blog_categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = BlogCategory::create($validator->validated());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = BlogCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:blog_categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = BlogCategory::findOrFail($id);
        $category->update($validator->validated());
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
