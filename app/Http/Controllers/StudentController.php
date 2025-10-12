<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Danh sách sinh viên
    public function index()
    {
        $students = Student::with('room')->get();
        return view('students.index', compact('students'));
    }

    // Form thêm sinh viên
    public function create()
    {
        $rooms = Room::withCount('students')->get();
        return view('students.create', compact('rooms'));
    }

    // Lưu sinh viên
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'student_code' => 'required|unique:students',
            'phone' => 'required',
            'room_number' => 'required',
        ]);

        $room = Room::where('name', $request->room_number)->first();

        if (!$room) {
            return back()->with('error', 'Phòng không tồn tại!');
        }

        if ($room->students()->count() >= $room->capacity) {
            return back()->with('error', 'Phòng đã đầy, không thể thêm sinh viên!');
        }

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Thêm sinh viên thành công!');
    }
}
