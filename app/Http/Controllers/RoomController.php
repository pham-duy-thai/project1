<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function adminOnly()
    {
        if (Auth::check() && Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Bạn không có quyền truy cập.');
        }
    }

    public function index()
    {
        $this->adminOnly();

        $rooms = Room::with(['building', 'floor'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.rooms.index', compact('rooms'))
            ->with('layout', 'layout2.theme');
    }

    public function create()
    {
        $this->adminOnly();

        $buildings = Building::all();
        $floors = collect(); // ban đầu rỗng, sẽ tự load bằng JS

        return view('admin.rooms.create', compact('buildings', 'floors'))
            ->with('layout', 'layout2.theme');
    }

    public function store(Request $request)
    {
        $this->adminOnly();

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_id'    => 'required|exists:floors,id',
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'capacity'    => 'required|integer|min:1',
            'gender'      => 'required|in:Nam,Nữ',
            'price'       => 'required|numeric|min:0',
        ]);

        Room::create([
            'building_id' => $request->building_id,
            'floor_id'    => $request->floor_id,
            'room_number' => $request->room_number,
            'capacity'    => $request->capacity,
            'gender'      => $request->gender,
            'price'       => $request->price,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Thêm phòng thành công!');
    }

    public function edit($id)
    {
        $this->adminOnly();

        $room = Room::with(['building', 'floor'])->findOrFail($id);
        $buildings = Building::all();
        $floors = Floor::where('building_id', $room->building_id)->get();

        return view('admin.rooms.edit', compact('room', 'buildings', 'floors'))
            ->with('layout', 'layout2.theme');
    }

    public function update(Request $request, $id)
    {
        $this->adminOnly();

        $room = Room::findOrFail($id);

        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_id'    => 'required|exists:floors,id',
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id,
            'capacity'    => 'required|integer|min:1',
            'gender'      => 'required|in:Nam,Nữ',
            'price'       => 'required|numeric|min:0',
        ]);

        $room->update([
            'building_id' => $request->building_id,
            'floor_id'    => $request->floor_id,
            'room_number' => $request->room_number,
            'capacity'    => $request->capacity,
            'gender'      => $request->gender,
            'price'       => $request->price,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật phòng thành công!');
    }

    public function destroy($id)
    {
        $this->adminOnly();

        $room = Room::findOrFail($id);
        $room->delete();

        return back()->with('success', 'Xóa phòng thành công!');
    }
}
