<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use App\Models\RoomType;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with(['building.dormitory', 'roomType']);
        
        // Filter by building
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by floor
        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }
        
        // Filter by room type
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        
        // Search by room number
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }
        
        $rooms = $query->orderBy('building_id')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->paginate(20);
        
        // Data for filters
        $dormitories = \App\Models\Dormitory::where('is_active', true)->orderBy('name')->get();
        $buildings = Building::where('is_active', true)->orderBy('name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        
        return view('rooms.index', compact('rooms', 'dormitories', 'buildings', 'roomTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::where('is_active', true)->orderBy('name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('capacity')->get();
        $amenities = Amenity::orderBy('name')->get();
        
        return view('rooms.create', compact('buildings', 'roomTypes', 'amenities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:50',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1|max:' . config('dormitory.rooms.max_capacity', 8),
            'area' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            // accept either flat array of IDs or array of objects with id
            'amenities.*' => 'sometimes',
            'is_active' => 'boolean'
        ]);
        
        // Check unique room number in building
        $exists = Room::where('building_id', $validated['building_id'])
            ->where('room_number', $validated['room_number'])
            ->exists();
        
        if ($exists) {
            return back()->withErrors([
                'room_number' => 'Số phòng này đã tồn tại trong tòa nhà.'
            ])->withInput();
        }
        
        // Upload images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                $images[] = $path;
            }
        }
        $validated['images'] = $images;
        
        $room = Room::create($validated);
        
        // Attach amenities (support both flat IDs and objects)
        if ($request->filled('amenities')) {
            foreach ($request->amenities as $amenity) {
                if (is_array($amenity) && isset($amenity['id'])) {
                    $room->amenities()->attach($amenity['id'], [
                        'quantity' => $amenity['quantity'] ?? 1,
                        'condition' => $amenity['condition'] ?? 'good'
                    ]);
                } else {
                    $room->amenities()->attach((int)$amenity, [
                        'quantity' => 1,
                        'condition' => 'good'
                    ]);
                }
            }
        }
        
        return redirect()->route('rooms.show', $room)
            ->with('success', 'Phòng đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load(['building.dormitory', 'roomType', 'amenities', 'contracts.student']);
        
        // Current occupants
        $currentOccupants = $room->contracts()
            ->where('status', 'active')
            ->with('student.user')
            ->get();
        
        // Maintenance history
        $maintenanceHistory = $room->maintenanceRequests()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('rooms.show', compact('room', 'currentOccupants', 'maintenanceHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $room->load('amenities');
        $buildings = Building::where('is_active', true)->orderBy('name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('capacity')->get();
        $amenities = Amenity::orderBy('name')->get();
        
        return view('rooms.edit', compact('room', 'buildings', 'roomTypes', 'amenities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:50',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1|max:' . config('dormitory.rooms.max_capacity', 8),
            'area' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'remove_images' => 'nullable|array',
            'amenities' => 'nullable|array',
            'amenities.*.id' => 'exists:amenities,id',
            'amenities.*.quantity' => 'integer|min:1',
            'is_active' => 'boolean'
        ]);
        
        // Check unique room number in building (except current room)
        $exists = Room::where('building_id', $validated['building_id'])
            ->where('room_number', $validated['room_number'])
            ->where('id', '!=', $room->id)
            ->exists();
        
        if ($exists) {
            return back()->withErrors([
                'room_number' => 'Số phòng này đã tồn tại trong tòa nhà.'
            ])->withInput();
        }
        
        // Handle image removal
        $currentImages = $room->images ?? [];
        if ($request->filled('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentImages)) !== false) {
                    Storage::disk('public')->delete($imageToRemove);
                    unset($currentImages[$key]);
                }
            }
            $currentImages = array_values($currentImages);
        }
        
        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                $currentImages[] = $path;
            }
        }
        $validated['images'] = $currentImages;
        
        $room->update($validated);
        
        // Sync amenities
        if ($request->has('amenities')) {
            $room->amenities()->detach();
            if ($request->filled('amenities')) {
                foreach ($request->amenities as $amenity) {
                    $room->amenities()->attach($amenity['id'], [
                        'quantity' => $amenity['quantity'] ?? 1,
                        'condition' => $amenity['condition'] ?? 'good'
                    ]);
                }
            }
        }
        
        return redirect()->route('rooms.show', $room)
            ->with('success', 'Phòng đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        // Check if room has active contracts
        if ($room->contracts()->where('status', 'active')->exists()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Không thể xóa phòng vì còn hợp đồng đang hoạt động.');
        }
        
        // Delete images
        if ($room->images) {
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $room->delete();
        
        return redirect()->route('rooms.index')
            ->with('success', 'Phòng đã được xóa thành công.');
    }
    
    /**
     * Get available rooms
     */
    public function available(Request $request)
    {
        $query = Room::with(['building.dormitory', 'roomType'])
            ->where('status', 'available')
            ->where('is_active', true)
            ->whereColumn('current_occupancy', '<', 'capacity');
        
        // Filter by building
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }
        
        // Filter by room type
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        
        // Filter by gender (from building)
        if ($request->filled('gender')) {
            $query->whereHas('building', function($q) use ($request) {
                $q->where(function($q2) use ($request) {
                    $q2->where('gender', $request->gender)
                       ->orWhere('gender', 'mixed');
                });
            });
        }
        
        $rooms = $query->orderBy('building_id')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->paginate(20);
        
        if ($request->wantsJson()) {
            return response()->json($rooms);
        }
        
        return view('rooms.available', compact('rooms'));
    }
}
