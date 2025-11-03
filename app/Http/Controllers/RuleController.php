<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::all();
        return view('rules.index', compact('rules'));
    }

    public function create()
    {
        return view('rules.create');
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required']);
        Rule::create($request->all());
        return redirect()->route('rules.index')->with('success', 'Thêm nội quy thành công');
    }

    public function edit(Rule $rule)
    {
        return view('rules.edit', compact('rule'));
    }

    public function update(Request $request, Rule $rule)
    {
        $request->validate(['content' => 'required']);
        $rule->update($request->all());
        return redirect()->route('rules.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Rule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Xóa thành công');
    }
}
