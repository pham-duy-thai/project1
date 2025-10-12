<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Danh sách phòng
    public function index()
    {
        $rooms = Room::withCount('students')->get();
        return view('rooms.index', compact('rooms'));
    }

    // Form thêm phòng
    public function create()
    {
        return view('rooms.create');
    }

    // Lưu phòng
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:rooms',
            'capacity' => 'required|integer|min:1',
            'status' => 'required',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Thêm phòng thành công!');
    }

    // Form sửa phòng
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    // Cập nhật phòng
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required|integer|min:1',
            'status' => 'required',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Cập nhật phòng thành công!');
    }

    // Xóa phòng
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Xóa phòng thành công!');
    }
}
