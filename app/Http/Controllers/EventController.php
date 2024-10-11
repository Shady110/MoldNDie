<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // Fetch all events
public function index(Request $request)
{
    $query = Event::query();

    // Check if the request has a 'sort' parameter
    if ($request->has('sort')) {
        $sortOrder = $request->get('sort');

        // Sort by 'newest' (default) or 'oldest'
        if ($sortOrder === 'Oldest first') {
            $query->orderBy('created_at', 'asc'); // Oldest first
        }
        if  ($sortOrder === 'Newest first'){
            $query->orderBy('created_at', 'desc'); // Newest first (default)
        }
    } else {
        // Default sorting by newest first if no 'sort' parameter
        $query->orderBy('created_at', 'desc');
    }

    // Retrieve events (you can also paginate if required)
    $events = $query->get();

    return response()->json($events, 200);
}
    // Store a new event
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'organizer_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = $validator->validated();

        // Handling image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $event = Event::create($data);
        return response()->json($event, 201);
    }

    // Show a single event by ID
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event, 200);
    }

    // Update an existing event
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'date' => 'sometimes|date',
            'location' => 'sometimes|string|max:255',
            'organizer_info' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $event = Event::findOrFail($id);
        $data = $validator->validated();

        // Handling image update
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $event->update($data);
        return response()->json($event, 200);
    }

    // Delete an event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(null, 204);
    }
}
