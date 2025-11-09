<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $statistics = Statistic::with('room')->get();
        return view('admin.statistics.index', compact('statistics'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        return view('admin.statistics.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        Statistic::create($r->all());
        return back()->with('success', 'Thêm thống kê thành công');
    }

    public function edit($id)
    {
        $stat = Statistic::findOrFail($id);
        return view('admin.statistics.edit', compact('stat'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $stat = Statistic::findOrFail($id);
        $stat->update($r->all());
        return back()->with('success', 'Cập nhật thống kê thành công');
    }

    public function destroy($id)
    {
        Statistic::destroy($id);
        return back()->with('success', 'Xóa thống kê thành công');
    }
}
