<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{


    // ✅ Thêm pagination thay vì get() tất cả
    public function index()
    {
        $students = Student::orderBy('student_code', 'asc')->paginate(20);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    // ✅ Cải thiện validation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_code' => 'required|string|max:50|unique:students,student_code',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Nam,Nữ,Khác',
            'class' => 'required|string|max:50',
            'phone' => 'nullable|regex:/^[0-9]{10,11}$/',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Thêm sinh viên thành công.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    // ✅ Dùng $validated thay vì $request->all()
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_code' => 'required|string|max:50|unique:students,student_code,' . $student->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Nam,Nữ,Khác',
            'class' => 'required|string|max:50',
            'phone' => 'nullable|regex:/^[0-9]{10,11}$/',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Cập nhật sinh viên thành công.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Xóa sinh viên thành công.');
    }
}
