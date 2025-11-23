<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\RoomRegistration;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['registration.student', 'registration.room'])->get();
        return view('admin.contracts.index', compact('contracts'));
    }

    public function create()
    {
        // Chỉ lấy đăng ký chưa có hợp đồng
        $registrations = RoomRegistration::with(['student', 'room'])
            ->whereDoesntHave('contract')
            ->get();

        return view('admin.contracts.create', compact('registrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_registration_id' => 'required|exists:room_registrations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        Contract::create($request->all());

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Tạo hợp đồng thành công.');
    }

    public function show($id)
    {
        $contract = Contract::with(['registration.student', 'registration.room'])
            ->findOrFail($id);

        return view('admin.contracts.show', compact('contract'));
    }

    public function edit($id)
    {
        $contract = Contract::with(['registration.student', 'registration.room'])
            ->findOrFail($id);

        return view('admin.contracts.edit', compact('contract'));
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $contract->update($request->all());

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Cập nhật hợp đồng thành công.');
    }

    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Xóa hợp đồng thành công.');
    }
}
