<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuleController extends Controller
{
    public function __construct()
    {
        // Chỉ cho admin truy cập
        $this->middleware('auth');
    }

    /**
     * 📋 Danh sách nội quy
     */
    public function index()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        $rules = Rule::orderBy('id', 'desc')->get();

        return view('admin.rules.index', compact('rules'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * ➕ Form thêm nội quy
     */
    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        return view('admin.rules.create')
            ->with('layout', 'layout2.theme');
    }

    /**
     * 💾 Lưu nội quy mới
     */
    public function store(Request $request)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255|unique:rules,title',
            'description' => 'nullable|string|max:2000',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề nội quy.',
            'title.unique'   => 'Nội quy này đã tồn tại.',
        ]);

        Rule::create($validated);

        return redirect()->route('admin.rules.index')->with('success', 'Thêm nội quy thành công!');
    }

    /**
     * ✏️ Form chỉnh sửa nội quy
     */
    public function edit($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $rule = Rule::findOrFail($id);

        return view('admin.rules.edit', compact('rule'))
            ->with('layout', 'layout2.theme');
    }

    /**
     * 🔁 Cập nhật nội quy
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $rule = Rule::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255|unique:rules,title,' . $rule->id,
            'description' => 'nullable|string|max:2000',
        ]);

        $rule->update($validated);

        return redirect()->route('admin.rules.index')->with('success', 'Cập nhật nội quy thành công!');
    }

    /**
     * 🗑️ Xóa nội quy
     */
    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        Rule::findOrFail($id)->delete();

        return back()->with('success', 'Xóa nội quy thành công!');
    }
}
