<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FloorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Danh sách tầng
    public function index()
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $floors = Floor::with('building')->get();
        return view('admin.floors.index', compact('floors'))->with('layout', 'layout2.theme');
    }

    // Form thêm
    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);
        $type = 'building';
        $buildings = Building::all();
        return view('admin.floors.create', compact('buildings'))->with('layout', 'layout2.theme');
    }

    // Lưu
    public function store(Request $r)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $r->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_number' => 'required|integer|min:1',
        ]);

        Floor::create($r->only('building_id', 'floor_number'));
        return redirect()->route('floors.index')->with('success', 'Thêm tầng thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $floor = Floor::findOrFail($id);
        $buildings = Building::all();
        return view('admin.floors.edit', compact('floor', 'buildings'))->with('layout', 'layout2.theme');
    }

    // Cập nhật
    public function update(Request $r, $id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $r->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_number' => 'required|integer|min:1',
        ]);

        $floor = Floor::findOrFail($id);
        $floor->update($r->only('building_id', 'floor_number'));

        return redirect()->route('floors.index')->with('success', 'Cập nhật tầng thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        Floor::destroy($id);
        return back()->with('success', 'Xóa tầng thành công!');
    }
}
