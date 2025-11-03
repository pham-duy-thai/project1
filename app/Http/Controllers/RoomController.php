<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('floor')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $floors = Floor::all();
        return view('rooms.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'floor_id' => 'required',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        Room::create($request->all());
        return redirect()->route('rooms.index')->with('success', 'Thêm phòng thành công');
    }

    public function edit(Room $room)
    {
        $floors = Floor::all();
        return view('rooms.edit', compact('room', 'floors'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
            'floor_id' => 'required',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $room->update($request->all());
        return redirect()->route('rooms.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
