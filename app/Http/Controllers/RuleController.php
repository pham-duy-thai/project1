<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\RuleAcknowledgment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Rule::query();

        if (request('status')) {
            $query->where('is_active', request('status') === 'active');
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        if (request('search')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.request('search').'%')
                  ->orWhere('content', 'like', '%'.request('search').'%');
            });
        }

        $rules = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(20);

        // For students, get acknowledgment status
        $acknowledgedRules = [];
        if (Auth::user()->isStudent() && Auth::user()->student) {
            $acknowledgedRules = RuleAcknowledgment::where('student_id', Auth::user()->student->id)
                ->pluck('rule_id')
                ->toArray();
        }

        return view('rules.index', compact('rules', 'acknowledgedRules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Handle checkbox values before validation
            $request->merge([
                'is_active' => $request->has('is_active') ? 1 : 0,
                'requires_acknowledgment' => $request->has('requires_acknowledgment') ? 1 : 0,
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|in:safety,hygiene,discipline,general',
                'severity' => 'required|in:low,medium,high',
                'penalty_amount' => 'nullable|numeric|min:0',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'required|boolean',
                'requires_acknowledgment' => 'required|boolean',
            ]);

            Rule::create($validated);

            return redirect()->route('rules.index')
                ->with('success', 'Tạo nội quy thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rule $rule)
    {
        $isAcknowledged = false;
        if (Auth::user()->isStudent() && Auth::user()->student) {
            $isAcknowledged = $rule->isAcknowledgedBy(Auth::user()->student->id);
        }

        return view('rules.show', compact('rule', 'isAcknowledged'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rule $rule)
    {
        return view('rules.edit', compact('rule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rule $rule)
    {
        try {
            // Handle checkbox values before validation
            $request->merge([
                'is_active' => $request->has('is_active') ? 1 : 0,
                'requires_acknowledgment' => $request->has('requires_acknowledgment') ? 1 : 0,
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|in:safety,hygiene,discipline,general',
                'severity' => 'required|in:low,medium,high',
                'penalty_amount' => 'nullable|numeric|min:0',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'required|boolean',
                'requires_acknowledgment' => 'required|boolean',
            ]);

            $rule->update($validated);

            return redirect()->route('rules.index')
                ->with('success', 'Cập nhật nội quy thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rule $rule)
    {
        $rule->delete();

        return redirect()->route('rules.index')
            ->with('success', 'Xóa nội quy thành công.');
    }

    /**
     * Student acknowledges a rule
     */
    public function acknowledge(Request $request, Rule $rule)
    {
        $user = Auth::user();
        
        if (!$user->isStudent() || !$user->student) {
            return back()->with('error', 'Chỉ sinh viên mới có thể xác nhận nội quy.');
        }

        $existing = RuleAcknowledgment::where('rule_id', $rule->id)
            ->where('student_id', $user->student->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Bạn đã xác nhận nội quy này rồi.');
        }

        RuleAcknowledgment::create([
            'rule_id' => $rule->id,
            'student_id' => $user->student->id,
            'acknowledged_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Xác nhận đã đọc nội quy thành công.');
    }
}
