<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'color' => 'nullable|string',
        ]);
        
        $event = Event::create($validated + [
            'created_by' => auth('principal')->id()
        ]);
        
        return response()->json($event, 201);
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