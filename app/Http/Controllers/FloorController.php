<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $floors = Floor::with('building')->orderBy('building_id')->orderBy('floor_number')->get();
        return view('floors.index', compact('floors'));
    }

    public function create()
    {
        $buildings = Building::all();
        return view('floors.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_number' => 'required|integer|min:1',
        ]);

        Floor::create($request->all());
        return redirect()->route('floors.index')->with('success', 'Tầng đã được thêm.');
    }

    public function edit(Floor $floor)
    {
        $buildings = Building::all();
        return view('floors.edit', compact('floor', 'buildings'));
    }

    public function update(Request $request, Floor $floor)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'floor_number' => 'required|integer|min:1',
        ]);

        $floor->update($request->all());
        return redirect()->route('floors.index')->with('success', 'Tầng đã được cập nhật.');
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        return redirect()->route('floors.index')->with('success', 'Tầng đã được xóa.');
    }
}
