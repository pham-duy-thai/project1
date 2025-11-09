<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        return view('admin.services.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        Service::create($r->only('name', 'price'));
        return back()->with('success', 'Thêm dịch vụ thành công');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $s = Service::findOrFail($id);
        $s->update($r->only('name', 'price'));
        return back()->with('success', 'Cập nhật dịch vụ thành công');
    }

    public function destroy($id)
    {
        Service::destroy($id);
        return back()->with('success', 'Xóa dịch vụ thành công');
    }
}
