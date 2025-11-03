<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $buildings = Building::orderBy('id', 'asc')->get();
        return view('buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('buildings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:buildings,name',
            'total_floors' => 'required|integer|min:1',
        ]);

        Building::create($request->all());

        return redirect()->route('buildings.index')->with('success', 'Tòa nhà đã được thêm thành công.');
    }

    public function edit(Building $building)
    {
        return view('buildings.edit', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $request->validate([
            'name' => 'required|unique:buildings,name,' . $building->id,
            'total_floors' => 'required|integer|min:1',
        ]);

        $building->update($request->all());

        return redirect()->route('buildings.index')->with('success', 'Tòa nhà đã được cập nhật.');
    }

    public function destroy(Building $building)
    {
        $building->delete();
        return redirect()->route('buildings.index')->with('success', 'Tòa nhà đã được xóa.');
    }
}
