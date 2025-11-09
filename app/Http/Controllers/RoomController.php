<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $rooms = Room::with('floor.building')->get();
        return view('admin.rooms.index', compact('rooms'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        $floors = Floor::all();
        return view('admin.rooms.create', compact('floors'))->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        $r->validate(['floor_id' => 'required', 'room_number' => 'required', 'capacity' => 'required', 'price' => 'required']);
        Room::create($r->all());
        return back()->with('success', 'Thêm phòng thành công');
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $floors = Floor::all();
        return view('admin.rooms.edit', compact('room', 'floors'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $room = Room::findOrFail($id);
        $room->update($r->all());
        return back()->with('success', 'Cập nhật phòng thành công');
    }

    public function destroy($id)
    {
        Room::destroy($id);
        return back()->with('success', 'Xóa phòng thành công');
    }
}
