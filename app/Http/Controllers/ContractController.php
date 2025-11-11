<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\RoomRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function __construct()
    {
        // Chỉ admin mới được quản lý hợp đồng
        $this->middleware('role:admin');
    }

    /**
     * Danh sách hợp đồng
     */
    public function index()
    {
        $contracts = Contract::with('roomRegistration.student.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.contracts.index', compact('contracts'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * Form tạo hợp đồng mới
     */
    public function create()
    {
        // Chỉ những đăng ký phòng đã được duyệt mới có thể tạo hợp đồng
        $registrations = RoomRegistration::with('student.user', 'room')
            ->where('status', 'approved')
            ->get();

        return view('admin.contracts.create', compact('registrations'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * Lưu hợp đồng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_registration_id' => 'required|exists:room_registrations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
        ], [
            'room_registration_id.required' => 'Vui lòng chọn đăng ký phòng.',
            'start_date.required' => 'Vui lòng nhập ngày bắt đầu.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        Contract::create([
            'room_registration_id' => $request->room_registration_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'deposit' => $request->deposit,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('contracts.index')->with('success', '✅ Tạo hợp đồng thành công!');
    }

    /**
     * Form chỉnh sửa hợp đồng
     */
    public function edit($id)
    {
        $contract = Contract::with('roomRegistration.student.user')->findOrFail($id);
        $registrations = RoomRegistration::with('student.user', 'room')->get();

        return view('admin.contracts.edit', compact('contract', 'registrations'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * Cập nhật hợp đồng
     */
    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);

        $request->validate([
            'room_registration_id' => 'required|exists:room_registrations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        $contract->update([
            'room_registration_id' => $request->room_registration_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'deposit' => $request->deposit,
            'status' => $request->status ?? $contract->status,
        ]);

        return redirect()->route('contracts.index')->with('success', '✅ Cập nhật hợp đồng thành công!');
    }

    /**
     * Xóa hợp đồng
     */
    public function destroy($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return back()->with('error', '❌ Không tìm thấy hợp đồng.');
        }

        $contract->delete();
        return back()->with('success', '🗑️ Xóa hợp đồng thành công!');
    }
}
