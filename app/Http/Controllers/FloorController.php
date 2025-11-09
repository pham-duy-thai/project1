<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $floors = Floor::with('building')->get();
        return view('admin.floors.index', compact('floors'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        $buildings = Building::all();
        return view('admin.floors.create', compact('buildings'))->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        Floor::create($r->all());
        return back()->with('success', 'Thêm tầng thành công');
    }

    public function edit($id)
    {
        $floor = Floor::findOrFail($id);
        $buildings = Building::all();
        return view('admin.floors.edit', compact('floor', 'buildings'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $f = Floor::findOrFail($id);
        $f->update($r->all());
        return back()->with('success', 'Cập nhật tầng thành công');
    }

    public function destroy($id)
    {
        Floor::destroy($id);
        return back()->with('success', 'Xóa tầng thành công');
    }
}
