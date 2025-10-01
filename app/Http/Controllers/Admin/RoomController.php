<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');
        $status = $request->get('status');

        $roomsQuery = Room::query();

        if ($search) {
            $roomsQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $roomsQuery->where('type', $type);
        }

        if ($status !== null) {
            $roomsQuery->where('is_available', $status);
        }

        $rooms = $roomsQuery->orderBy('name')->paginate(15);

        $roomTypes = ['general', 'laboratory', 'computer_lab', 'science_lab', 'library', 'gymnasium', 'auditorium', 'special'];

        return view('admin.rooms.index', compact('rooms', 'roomTypes', 'search', 'type', 'status'));
    }

    /**
     * Show the form for creating a new room
     */
    public function create()
    {
        $roomTypes = ['general', 'laboratory', 'computer_lab', 'science_lab', 'library', 'gymnasium', 'auditorium', 'special'];
        return view('admin.rooms.create', compact('roomTypes'));
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code',
            'type' => 'required|in:general,laboratory,computer_lab,science_lab,library,gymnasium,auditorium,special',
            'capacity' => 'required|integer|min:1|max:1000',
            'location' => 'nullable|string|max:255',
            'equipment' => 'nullable|array',
            'is_available' => 'boolean',
            'special_requirements' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        $room = Room::create($validated);

        $successMessage = "✅ Room Created Successfully!\n\n";
        $successMessage .= "📋 Room Details:\n";
        $successMessage .= "🏢 Name: {$room->name}\n";
        $successMessage .= "🔢 Code: {$room->code}\n";
        $successMessage .= "📝 Type: " . ucfirst(str_replace('_', ' ', $room->type)) . "\n";
        $successMessage .= "👥 Capacity: {$room->capacity}\n";
        $successMessage .= "📍 Location: " . ($room->location ?: 'Not specified') . "\n";
        $successMessage .= "✅ Status: " . ($room->is_available ? 'Available' : 'Unavailable') . "\n";
        $successMessage .= "🕒 Created on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.rooms.index')
            ->with('success', $successMessage);
    }

    /**
     * Display the specified room
     */
    public function show(Room $room)
    {
        $room->load('schedules.teacher', 'schedules.subject', 'schedules.section');
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room
     */
    public function edit(Room $room)
    {
        $roomTypes = ['general', 'laboratory', 'computer_lab', 'science_lab', 'library', 'gymnasium', 'auditorium', 'special'];
        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code,' . $room->id,
            'type' => 'required|in:general,laboratory,computer_lab,science_lab,library,gymnasium,auditorium,special',
            'capacity' => 'required|integer|min:1|max:1000',
            'location' => 'nullable|string|max:255',
            'equipment' => 'nullable|array',
            'is_available' => 'boolean',
            'special_requirements' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        $room->update($validated);

        $successMessage = "✅ Room Updated Successfully!\n\n";
        $successMessage .= "📋 Updated Room Details:\n";
        $successMessage .= "🏢 Name: {$room->name}\n";
        $successMessage .= "🔢 Code: {$room->code}\n";
        $successMessage .= "📝 Type: " . ucfirst(str_replace('_', ' ', $room->type)) . "\n";
        $successMessage .= "👥 Capacity: {$room->capacity}\n";
        $successMessage .= "📍 Location: " . ($room->location ?: 'Not specified') . "\n";
        $successMessage .= "✅ Status: " . ($room->is_available ? 'Available' : 'Unavailable') . "\n";
        $successMessage .= "🕒 Updated on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.rooms.index')
            ->with('success', $successMessage);
    }

    /**
     * Remove the specified room
     */
    public function destroy(Room $room)
    {
        // Check if room has active schedules
        $activeSchedules = $room->schedules()->where('status', 'active')->count();
        
        if ($activeSchedules > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete room. It has {$activeSchedules} active schedule(s). Please remove or reassign the schedules first.");
        }

        $roomName = $room->name;
        $roomCode = $room->code;
        
        $room->delete();

        $successMessage = "✅ Room Deleted Successfully!\n\n";
        $successMessage .= "📋 Deleted Room Details:\n";
        $successMessage .= "🏢 Name: {$roomName}\n";
        $successMessage .= "🔢 Code: {$roomCode}\n";
        $successMessage .= "🕒 Deleted on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.rooms.index')
            ->with('success', $successMessage);
    }

    /**
     * Toggle room availability
     */
    public function toggleAvailability(Room $room)
    {
        $room->is_available = !$room->is_available;
        $room->save();

        $status = $room->is_available ? 'Available' : 'Unavailable';
        
        return redirect()->back()
            ->with('success', "Room '{$room->name}' is now {$status}.");
    }
}
