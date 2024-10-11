<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the logo image
            'description' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id', // Ensure the category exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validatedData = $validator->validated();

        // Handle logo image upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/suppliers'), $imageName); // Save logo to 'images/suppliers' folder
            $logoPath = 'images/suppliers/' . $imageName;
        }

        // Add the logo path to the validated data
        $validatedData['logo'] = $logoPath;

        // Create a new supplier with the validated data
        $supplier = Supplier::create($validatedData);
        return response()->json($supplier, 201);
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the logo image
            'description' => 'sometimes|string',
            'contact_info' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,category_id', // Ensure the category exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $supplier = Supplier::findOrFail($id);
        $validatedData = $validator->validated();

        // Handle logo image upload if present
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/suppliers'), $imageName); // Save logo to 'images/suppliers' folder
            $validatedData['logo'] = 'images/suppliers/' . $imageName;
        }

        // Update the supplier with the validated data
        $supplier->update($validatedData);
        return response()->json($supplier);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(null, 204);
    }
}
