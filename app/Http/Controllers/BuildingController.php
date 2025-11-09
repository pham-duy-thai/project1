<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $buildings = Building::all();
        return view('admin.buildings.index', compact('buildings'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        return view('admin.buildings.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        $r->validate(['name' => 'required|unique:buildings']);
        Building::create($r->only('name', 'total_floors'));
        return back()->with('success', 'Thêm tòa nhà thành công');
    }

    public function edit($id)
    {
        $b = Building::findOrFail($id);
        return view('admin.buildings.edit', compact('b'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $b = Building::findOrFail($id);
        $b->update($r->only('name', 'total_floors'));
        return back()->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Building::destroy($id);
        return back()->with('success', 'Xóa thành công');
    }
}
