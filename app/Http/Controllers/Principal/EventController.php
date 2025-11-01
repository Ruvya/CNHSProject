<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // Fetch all events
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    // (Optional) Store a new event
    public function store(Request $request)
    {
        // Ensure JSON response even when validation fails (for fetch/AJAX callers)
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422)
                ->header('Cache-Control', 'no-store');
        }

        try {
            $payload = $validator->validated();
            $payload['created_by'] = auth('principal')->id();

            $event = Event::create($payload);

            return response()->json($event, 201)
                ->header('Cache-Control', 'no-store');
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Unable to save event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show a single event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    // Update an event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'color' => 'nullable|string',
        ]);
        $event->update($validated);
        return response()->json($event);
    }

    // Delete an event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['success' => true]);
    }
} 