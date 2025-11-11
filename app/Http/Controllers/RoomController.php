<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        $rooms = Room::with('building')->orderBy('id', 'desc')->get();

        return view('admin.rooms.index', compact('rooms'))
            ->with('layout', 'layout2.theme');
    }

    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $buildings = Building::all();

        return view('admin.rooms.create', compact('buildings'))
            ->with('layout', 'layout2.theme');
    }

    public function store(Request $request)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $request->validate([
            'building_id'  => 'required|exists:buildings,id',
            'floor_number' => 'required|integer|min:1',
            'room_number'  => 'required|string|max:50|unique:rooms,room_number',
            'capacity'     => 'required|integer|min:1',
            'gender'       => 'required|in:nam,nu',
            'price'        => 'required|numeric|min:0',
        ], [
            'building_id.required' => 'Vui lòng chọn tòa nhà.',
            'floor_number.required' => 'Vui lòng chọn tầng.',
            'room_number.unique' => 'Số phòng đã tồn tại.',
        ]);

        Room::create([
            'building_id'  => $request->building_id,
            'floor_number' => $request->floor_number,
            'room_number'  => $request->room_number,
            'capacity'     => $request->capacity,
            'gender'       => $request->gender,
            'price'        => $request->price,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Thêm phòng thành công!');
    }

    public function edit($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $room = Room::with('building')->findOrFail($id);
        $buildings = Building::all();

        return view('admin.rooms.edit', compact('room', 'buildings'))
            ->with('layout', 'layout2.theme');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $room = Room::findOrFail($id);

        $request->validate([
            'building_id'  => 'required|exists:buildings,id',
            'floor_number' => 'required|integer|min:1',
            'room_number'  => 'required|string|max:50|unique:rooms,room_number,' . $room->id,
            'capacity'     => 'required|integer|min:1',
            'gender'       => 'required|in:nam,nu',
            'price'        => 'required|numeric|min:0',
        ]);

        $room->update([
            'building_id'  => $request->building_id,
            'floor_number' => $request->floor_number,
            'room_number'  => $request->room_number,
            'capacity'     => $request->capacity,
            'gender'       => $request->gender,
            'price'        => $request->price,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Cập nhật phòng thành công!');
    }

    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $room = Room::findOrFail($id);
        $room->delete();

        return back()->with('success', 'Xóa phòng thành công!');
    }
}
