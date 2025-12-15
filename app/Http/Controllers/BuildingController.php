<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Dormitory;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buildings = Building::with('dormitory')
            ->withCount('rooms')
            ->when(request('dormitory_id'), function($query) {
                $query->where('dormitory_id', request('dormitory_id'));
            })
            ->when(request('gender'), function($query) {
                $query->where('gender', request('gender'));
            })
            ->when(request('status'), function($query) {
                $query->where('is_active', request('status') === 'active');
            })
            ->when(request('search'), function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('code', 'like', '%' . request('search') . '%');
                });
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->paginate(15);
        
        $dormitories = Dormitory::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('buildings.index', compact('buildings', 'dormitories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dormitories = Dormitory::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('buildings.create', compact('dormitories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dormitory_id' => 'required|exists:dormitories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:buildings,code',
            'floors' => 'required|integer|min:1|max:50',
            'gender' => 'required|in:male,female,mixed',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $building = Building::create($validated);
        
        return redirect()->route('buildings.show', $building)
            ->with('success', 'Tòa nhà đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        $building->load(['dormitory', 'rooms.roomType']);
        
        // Thống kê tổng quan
        $stats = [
            'total_rooms' => $building->rooms()->count(),
            'occupied_rooms' => $building->rooms()->where('status', 'occupied')->count(),
            'available_rooms' => $building->rooms()->where('status', 'available')->count(),
            'maintenance_rooms' => $building->rooms()->where('status', 'maintenance')->count(),
        ];
        
        // Tính tỷ lệ lấp đầy
        $stats['occupancy_rate'] = $stats['total_rooms'] > 0 
            ? round(($stats['occupied_rooms'] / $stats['total_rooms']) * 100, 1) 
            : 0;
        
        // Thống kê theo tầng
        $floorStats = [];
        for ($floor = 1; $floor <= $building->floors; $floor++) {
            $floorStats[$floor] = [
                'total' => $building->rooms()->where('floor', $floor)->count(),
                'occupied' => $building->rooms()->where('floor', $floor)->where('status', 'occupied')->count(),
                'available' => $building->rooms()->where('floor', $floor)->where('status', 'available')->count(),
                'maintenance' => $building->rooms()->where('floor', $floor)->where('status', 'maintenance')->count(),
            ];
        }
        
        // Nhóm phòng theo tầng
        $roomsByFloor = $building->rooms->groupBy('floor');
        
        return view('buildings.show', compact('building', 'stats', 'floorStats', 'roomsByFloor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        $dormitories = Dormitory::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('buildings.edit', compact('building', 'dormitories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'dormitory_id' => 'required|exists:dormitories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:buildings,code,' . $building->id,
            'floors' => 'required|integer|min:1|max:50',
            'gender' => 'required|in:male,female,mixed',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $building->update($validated);
        
        return redirect()->route('buildings.show', $building)
            ->with('success', 'Tòa nhà đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        // Kiểm tra xem có phòng nào không
        if ($building->rooms()->count() > 0) {
            return redirect()->route('buildings.index')
                ->with('error', 'Không thể xóa tòa nhà vì còn phòng.');
        }
        
        $building->delete();
        
        return redirect()->route('buildings.index')
            ->with('success', 'Tòa nhà đã được xóa thành công.');
    }
}
