<?php

namespace App\Http\Controllers;

use App\Models\RoomRegistration;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 📋 Danh sách đăng ký phòng
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'student') {
            $student = $user->student;

            if (!$student) {
                abort(403, 'Không tìm thấy thông tin sinh viên.');
            }

            $registrations = RoomRegistration::with(['room.building'])
                ->where('student_id', $student->id)
                ->orderByDesc('created_at')
                ->get();

            return view('student.registrations.index', compact('registrations'))
                ->with('layout', 'layout1.app');
        }

        if ($user->role->name === 'admin') {
            $registrations = RoomRegistration::with(['room.building', 'student'])
                ->orderByDesc('created_at')
                ->get();

            return view('admin.registrations.index', compact('registrations'))
                ->with('layout', 'layout2.theme');
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }

    /**
     * 📝 Form đăng ký phòng (Sinh viên)
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role->name !== 'student') {
            abort(403, 'Chỉ sinh viên mới được đăng ký phòng.');
        }

        $rooms = Room::with('building')->orderBy('building_id')->get();

        return view('student.registrations.create', compact('rooms'))
            ->with('layout', 'layout1.app');
    }

    /**
     * 💾 Lưu đăng ký mới
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name !== 'student') {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $student = $user->student;
        if (!$student) {
            abort(403, 'Không tìm thấy thông tin sinh viên.');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ], [
            'room_id.required' => 'Vui lòng chọn phòng muốn đăng ký.',
            'room_id.exists' => 'Phòng không tồn tại.',
        ]);

        // ✅ Kiểm tra sinh viên đã có đăng ký đang chờ hoặc đã duyệt chưa
        $existing = RoomRegistration::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Bạn đã có đơn đăng ký hoặc đang ở trong phòng.');
        }

        RoomRegistration::create([
            'student_id' => $student->id,
            'room_id' => $request->room_id,
            'registration_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('registrations.index')
            ->with('success', 'Đăng ký phòng thành công! Vui lòng chờ duyệt.');
    }

    /**
     * 🔁 Cập nhật trạng thái (Admin)
     */
    public function updateStatus($id, $status)
    {
        $user = Auth::user();

        if ($user->role->name !== 'admin') {
            abort(403, 'Chỉ admin mới có thể cập nhật trạng thái.');
        }

        $registration = RoomRegistration::findOrFail($id);

        $validStatuses = ['pending', 'approved', 'rejected', 'active'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        // ✅ Nếu duyệt => chuyển sang approved
        if ($status === 'approved') {
            $registration->update([
                'status' => 'approved',
                'registration_date' => now(),
            ]);
        }

        // ✅ Nếu active => đang ở trong phòng
        if ($status === 'active') {
            $registration->update([
                'status' => 'active',
                'registration_date' => now(),
            ]);
        }

        // ✅ Nếu reject => từ chối
        if ($status === 'rejected') {
            $registration->update(['status' => 'rejected']);
        }

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * 📆 Kết thúc hợp đồng (Admin)
     */
    public function endRegistration($id)
    {
        $user = Auth::user();

        if ($user->role->name !== 'admin') {
            abort(403, 'Chỉ admin mới có thể kết thúc hợp đồng.');
        }

        $registration = RoomRegistration::findOrFail($id);

        if ($registration->status !== 'active') {
            return back()->with('error', 'Chỉ có thể kết thúc các đăng ký đang hoạt động.');
        }

        $registration->update([
            'status' => 'completed',
            'end_date' => now(),
        ]);

        return back()->with('success', 'Đã kết thúc hợp đồng ở trọ.');
    }
    public function show($id)
    {
        $registration = RoomRegistration::with(['student', 'room.building'])->findOrFail($id);

        if (Auth::user()->role->name !== 'admin') {
            abort(403, 'Chỉ admin mới được xem chi tiết.');
        }

        return view('admin.registrations.show', compact('registration'))
            ->with('layout', 'layout2.theme');
    }
    /**
     * 🗑️ Xóa đăng ký (Sinh viên hoặc Admin)
     */
    public function destroy($id)
    {
        $registration = RoomRegistration::findOrFail($id);
        $user = Auth::user();

        // Sinh viên chỉ được xóa đơn của mình nếu đang pending
        if ($user->role->name === 'student') {
            if ($registration->student_id !== $user->student->id) {
                abort(403, 'Bạn không thể xóa đơn của người khác.');
            }

            if ($registration->status !== 'pending') {
                return back()->with('error', 'Không thể xóa đơn đã duyệt.');
            }
        }

        $registration->delete();

        return back()->with('success', 'Xóa đăng ký thành công!');
    }
}
