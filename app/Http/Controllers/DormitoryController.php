<?php

namespace App\Http\Controllers;

use App\Models\Dormitory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DormitoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dormitories = Dormitory::withCount([
            'buildings',
            'rooms',
            'rooms as occupied_rooms' => function ($query) {
                $query->where('status', 'occupied');
            }
        ])
        ->orderBy('is_active', 'desc')
        ->orderBy('name')
        ->get()
        ->map(function ($dormitory) {
            // Đếm tổng sinh viên qua contracts
            $dormitory->total_students = DB::table('contracts')
                ->join('rooms', 'contracts.room_id', '=', 'rooms.id')
                ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
                ->where('buildings.dormitory_id', $dormitory->id)
                ->where('contracts.status', 'active')
                ->distinct('contracts.student_id')
                ->count('contracts.student_id');
            
            $dormitory->total_rooms = $dormitory->rooms_count;
            
            return $dormitory;
        });
        
        return view('dormitories.index', compact('dormitories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dormitories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:dormitories,code',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'total_capacity' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $dormitory = Dormitory::create($validated);
        
        return redirect()->route('dormitories.show', $dormitory)
            ->with('success', 'Ký túc xá đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dormitory $dormitory)
    {
        $dormitory->load(['buildings.rooms']);
        
        // Thống kê
        $stats = [
            'total_buildings' => $dormitory->buildings()->count(),
            'total_rooms' => $dormitory->rooms()->count(),
            'occupied_rooms' => $dormitory->rooms()->where('status', 'occupied')->count(),
            'available_rooms' => $dormitory->rooms()->where('status', 'available')->count(),
            'maintenance_rooms' => $dormitory->rooms()->where('status', 'maintenance')->count(),
            'total_students' => DB::table('contracts')
                ->join('rooms', 'contracts.room_id', '=', 'rooms.id')
                ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
                ->where('buildings.dormitory_id', $dormitory->id)
                ->where('contracts.status', 'active')
                ->distinct('contracts.student_id')
                ->count('contracts.student_id')
        ];
        
        // Tính tỷ lệ lấp đầy
        $stats['occupancy_rate'] = $stats['total_rooms'] > 0 
            ? round(($stats['occupied_rooms'] / $stats['total_rooms']) * 100, 1) 
            : 0;
        
        return view('dormitories.show', compact('dormitory', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dormitory $dormitory)
    {
        return view('dormitories.edit', compact('dormitory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dormitory $dormitory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:dormitories,code,' . $dormitory->id,
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'total_capacity' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $dormitory->update($validated);
        
        return redirect()->route('dormitories.show', $dormitory)
            ->with('success', 'Ký túc xá đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dormitory $dormitory)
    {
        // Kiểm tra xem có tòa nhà nào không
        if ($dormitory->buildings()->count() > 0) {
            return redirect()->route('dormitories.index')
                ->with('error', 'Không thể xóa ký túc xá vì còn tòa nhà.');
        }
        
        $dormitory->delete();
        
        return redirect()->route('dormitories.index')
            ->with('success', 'Ký túc xá đã được xóa thành công.');
    }
}
