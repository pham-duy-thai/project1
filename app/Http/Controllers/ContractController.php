<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\RoomRegistration;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $contracts = Contract::with('roomRegistration.student')->get();
        return view('admin.contracts.index', compact('contracts'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        $registrations = RoomRegistration::where('status', 'approved')->get();
        return view('admin.contracts.create', compact('registrations'))->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        Contract::create($r->all());
        return redirect()->route('contracts.index')->with('success', 'Tạo hợp đồng thành công');
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $registrations = RoomRegistration::all();
        return view('admin.contracts.edit', compact('contract', 'registrations'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $contract = Contract::findOrFail($id);
        $contract->update($r->all());
        return back()->with('success', 'Cập nhật hợp đồng thành công');
    }

    public function destroy($id)
    {
        Contract::destroy($id);
        return back()->with('success', 'Xóa hợp đồng thành công');
    }
}
