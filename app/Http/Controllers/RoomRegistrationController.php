<?php

namespace App\Http\Controllers;

use App\Models\RoomRegistration;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomRegistrationController extends Controller
{
    public function index()
    {
        $registrations = RoomRegistration::with(['student', 'room'])->get();
        return view('room_registrations.index', compact('registrations'));
    }

    public function create()
    {
        $students = Student::all();
        $rooms = Room::all();
        return view('room_registrations.create', compact('students', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'room_id' => 'required',
            'registration_date' => 'required|date',
        ]);

        RoomRegistration::create($request->all());
        return redirect()->route('room-registrations.index')->with('success', 'Đăng ký phòng thành công');
    }

    public function edit(RoomRegistration $roomRegistration)
    {
        $students = Student::all();
        $rooms = Room::all();
        return view('room_registrations.edit', compact('roomRegistration', 'students', 'rooms'));
    }

    public function update(Request $request, RoomRegistration $roomRegistration)
    {
        $request->validate([
            'student_id' => 'required',
            'room_id' => 'required',
            'registration_date' => 'required|date',
        ]);

        $roomRegistration->update($request->all());
        return redirect()->route('room-registrations.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(RoomRegistration $roomRegistration)
    {
        $roomRegistration->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
