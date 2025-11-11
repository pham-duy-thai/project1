<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Trang danh sách sinh viên (chỉ admin được xem)
     */
    public function index()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        $students = Student::orderBy('created_at', 'desc')->get();
        return view('admin.students.index', compact('students'))->with('layout', 'layout2.theme');
    }

    /**
     * Form thêm sinh viên mới
     */
    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        return view('admin.students.create')->with('layout', 'layout2.theme');
    }

    /**
     * Lưu sinh viên mới
     */
    public function store(Request $request)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $request->validate([
            'student_code' => 'required|unique:students,student_code',
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'class' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'status' => 'required|string',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Thêm sinh viên thành công!');
    }

    /**
     * Form chỉnh sửa sinh viên
     */
    public function edit($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student'))->with('layout', 'layout2.theme');
    }

    /**
     * Cập nhật sinh viên
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $student = Student::findOrFail($id);

        $request->validate([
            'student_code' => 'required|unique:students,student_code,' . $id,
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'class' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'status' => 'required|string',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Cập nhật sinh viên thành công!');
    }

    /**
     * Xóa sinh viên
     */
    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        Student::destroy($id);
        return back()->with('success', 'Xóa sinh viên thành công!');
    }

    /**
     * Sinh viên xem thông tin cá nhân (layout1)
     */
    public function profile()
    {
        $student = Auth::user()->student ?? null;

        if (!$student) {
            abort(404, 'Không tìm thấy thông tin sinh viên.');
        }

        return view('student.profile', compact('student'))->with('layout', 'layout1.app');
    }
}
