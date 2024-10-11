<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'expires_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $subscription = Subscription::create($validator->validated());
        return response()->json($subscription, 201);
    }

    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);
        return response()->json($subscription);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|max:50',
            'expires_at' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $subscription = Subscription::findOrFail($id);
        $subscription->update($validator->validated());
        return response()->json($subscription);
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();
        return response()->json(null, 204);
    }
}
