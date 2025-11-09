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
        $this->middleware('role:admin')->except(['index', 'create', 'store']);
        $this->middleware('role:student')->only(['index', 'create', 'store']);
    }

    public function index()
    {
        if (Auth::user()->role->name === 'student') {
            $studentId = Auth::user()->student->id;
            $registrations = RoomRegistration::with('room')->where('student_id', $studentId)->get();
            return view('student.registrations.index', compact('registrations'))->with('layout', 'layout1.app');
        }

        $registrations = RoomRegistration::with(['room', 'student'])->get();
        return view('admin.registrations.index', compact('registrations'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        $rooms = Room::all();
        return view('student.registrations.create', compact('rooms'))->with('layout', 'layout1.app');
    }

    public function store(Request $r)
    {
        $studentId = Auth::user()->student->id;
        RoomRegistration::create([
            'student_id' => $studentId,
            'room_id' => $r->room_id,
            'registration_date' => now(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đăng ký phòng thành công, chờ duyệt.');
    }

    public function updateStatus($id, $status)
    {
        $r = RoomRegistration::findOrFail($id);
        $r->update(['status' => $status]);
        return back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function destroy($id)
    {
        RoomRegistration::destroy($id);
        return back()->with('success', 'Xóa đăng ký thành công');
    }
}
