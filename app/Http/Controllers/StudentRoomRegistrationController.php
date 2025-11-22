<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentRoomRegistrationController extends Controller
{
    // Hiển thị lịch sử + trạng thái đăng ký
    public function index()
    {
        $registrations = RoomRegistration::with(['room.building'])
            ->where('student_id', Auth::id())
            ->latest()
            ->get();

        return view('student.registrations.index', compact('registrations'));
    }

    // Hiển thị form đăng ký phòng
    public function create()
    {
        $rooms = Room::where('capacity', '>', 0)  // chỉ phòng còn chỗ
            ->orderBy('building_id')
            ->get();

        return view('student.registrations.create', compact('rooms'));
    }

    // Lưu yêu cầu đăng ký
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'registration_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:registration_date',
        ]);

        RoomRegistration::create([
            'student_id' => Auth::id(),
            'room_id' => $request->room_id,
            'registration_date' => $request->registration_date,
            'end_date' => $request->end_date,
            'status' => 'pending'
        ]);

        return redirect()->route('student.registrations.index')
            ->with('success', 'Yêu cầu đã được gửi! Vui lòng chờ duyệt.');
    }
}
