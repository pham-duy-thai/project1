<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->except(['dashboard', 'profile']);
        $this->middleware('role:student')->only(['dashboard', 'profile']);
    }

    public function index()
    {
        $students = Student::with('user')->get();
        return view('admin.students.index', compact('students'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        return view('admin.students.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $request)
    {
        Student::create($request->all());
        return redirect()->route('students.index')->with('success', 'Thêm sinh viên thành công');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student'))->with('layout', 'layout2.theme');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Cập nhật sinh viên thành công');
    }

    public function destroy($id)
    {
        Student::destroy($id);
        return back()->with('success', 'Xóa sinh viên thành công');
    }

    public function dashboard()
    {
        $student = Auth::user()->student;
        return view('student.dashboard', compact('student'))->with('layout', 'layout1.app');
    }

    public function profile()
    {
        $student = Auth::user()->student;
        return view('student.profile', compact('student'))->with('layout', 'layout1.app');
    }
}
