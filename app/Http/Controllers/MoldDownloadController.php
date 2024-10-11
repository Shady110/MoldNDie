<?php

namespace App\Http\Controllers;

use App\Models\MoldDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoldDownloadController extends Controller
{
    public function index()
    {
        return MoldDownload::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mold_id' => 'required|exists:molds,id',
            'user_id' => 'required|exists:users,id',
            'download_link' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $download = MoldDownload::create($validator->validated());
        return response()->json($download, 201);
    }

    public function show($id)
    {
        $download = MoldDownload::findOrFail($id);
        return response()->json($download);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'download_link' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $download = MoldDownload::findOrFail($id);
        $download->update($validator->validated());
        return response()->json($download);
    }

    public function destroy($id)
    {
        $download = MoldDownload::findOrFail($id);
        $download->delete();
        return response()->json(null, 204);
    }
}
