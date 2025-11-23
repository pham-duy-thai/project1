<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Danh sách tòa
    public function index()
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $buildings = Building::orderBy('created_at', 'desc')->get();
        return view('admin.buildings.index', compact('buildings'))->with('layout', 'layout2.theme');
    }

    // Form thêm
    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);
        $type = 'building';
        return view('admin.buildings.create')->with('layout', 'layout2.theme');
    }

    // Lưu
    public function store(Request $r)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $r->validate([
            'name' => 'required|unique:buildings,name',
            'total_floors' => 'required|integer|min:1',
        ]);

        Building::create($r->only('name', 'total_floors'));
        return redirect()->route('admin.buildings.index')->with('success', 'Thêm tòa nhà thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);
        $building = Building::findOrFail($id);
        return view('admin.buildings.edit', compact('building'))->with('layout', 'layout2.theme');
    }

    // Cập nhật
    public function update(Request $r, $id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        $r->validate([
            'name' => 'required|unique:buildings,name,' . $id,
            'total_floors' => 'required|integer|min:1',
        ]);

        $building = Building::findOrFail($id);
        $building->update($r->only('name', 'total_floors'));
        return redirect()->route('admin.buildings.index')->with('success', 'Cập nhật thành công!');
    }

    // Xóa
    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') abort(403);

        Building::destroy($id);
        return back()->with('success', 'Xóa tòa nhà thành công!');
    }
}
