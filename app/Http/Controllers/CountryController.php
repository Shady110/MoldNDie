<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function index()
    {
        return Country::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'required|string|max:10|unique:countries,code',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $country = Country::create($validator->validated());
        return response()->json($country, 201);
    }

    public function show($id)
    {
        $country = Country::findOrFail($id);
        return response()->json($country);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:countries,name,' . $id,
            'code' => 'sometimes|string|max:10|unique:countries,code,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $country = Country::findOrFail($id);
        $country->update($validator->validated());
        return response()->json($country);
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return response()->json(null, 204);
    }
}
