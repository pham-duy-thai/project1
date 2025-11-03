<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['student', 'room'])->get();
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $students = Student::all();
        $rooms = Room::all();
        return view('contracts.create', compact('students', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'room_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required',
        ]);

        Contract::create($request->all());
        return redirect()->route('contracts.index')->with('success', 'Tạo hợp đồng thành công');
    }

    public function edit(Contract $contract)
    {
        $students = Student::all();
        $rooms = Room::all();
        return view('contracts.edit', compact('contract', 'students', 'rooms'));
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'student_id' => 'required',
            'room_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required',
        ]);

        $contract->update($request->all());
        return redirect()->route('contracts.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
