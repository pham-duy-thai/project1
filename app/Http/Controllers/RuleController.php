<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $rules = Rule::all();
        return view('admin.rules.index', compact('rules'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        return view('admin.rules.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        Rule::create($r->only('title', 'description'));
        return back()->with('success', 'Thêm nội quy thành công');
    }

    public function edit($id)
    {
        $rule = Rule::findOrFail($id);
        return view('admin.rules.edit', compact('rule'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $rule = Rule::findOrFail($id);
        $rule->update($r->only('title', 'description'));
        return back()->with('success', 'Cập nhật nội quy thành công');
    }

    public function destroy($id)
    {
        Rule::destroy($id);
        return back()->with('success', 'Xóa nội quy thành công');
    }
}
