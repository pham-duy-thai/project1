<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Violation::with(['student.user', 'room', 'reporter']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        
        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        // Filter by fine status
        if ($request->filled('fine_status')) {
            $query->where('fine_status', $request->fine_status);
        }
        
        // Search by violation code or title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('violation_code', 'like', '%' . $search . '%')
                  ->orWhere('title', 'like', '%' . $search . '%');
            });
        }
        
        $violations = $query->orderBy('violation_date', 'desc')
            ->paginate(20);
        
        return view('violations.index', compact('violations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::where('status', 'active')
            ->with('user')
            ->orderBy('student_code')
            ->get();
        
        $rooms = Room::with('building.dormitory')
            ->where('is_active', true)
            ->orderBy('building_id')
            ->orderBy('room_number')
            ->get();
        
        return view('violations.create', compact('students', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'nullable|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:minor,moderate,serious,critical',
            'violation_date' => 'required|date|before_or_equal:today',
            'fine_amount' => 'required|numeric|min:0',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|max:5120' // 5MB max per file
        ]);
        
        // Generate violation code
        $violationCode = 'VIO' . date('Ymd') . str_pad(Violation::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Upload evidence files
        $evidenceFiles = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('violations', 'public');
                $evidenceFiles[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }
        
        // Create violation
        $violation = Violation::create([
            'violation_code' => $violationCode,
            'student_id' => $validated['student_id'],
            'room_id' => $validated['room_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'severity' => $validated['severity'],
            'violation_date' => $validated['violation_date'],
            'fine_amount' => $validated['fine_amount'],
            'fine_status' => 'unpaid',
            'reported_by' => Auth::id(),
            'evidence' => $evidenceFiles,
            'status' => 'pending'
        ]);
        
        return redirect()->route('violations.show', $violation)
            ->with('success', 'Vi phạm đã được ghi nhận.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Violation $violation)
    {
        $violation->load([
            'student.user',
            'room.building',
            'reporter'
        ]);
        
        return view('violations.show', compact('violation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Violation $violation)
    {
        if ($violation->status === 'resolved') {
            return redirect()->route('violations.show', $violation)
                ->with('error', 'Không thể chỉnh sửa vi phạm đã giải quyết.');
        }
        
        $students = Student::where('status', 'active')
            ->with('user')
            ->orderBy('student_code')
            ->get();
        
        $rooms = Room::with('building.dormitory')
            ->where('is_active', true)
            ->orderBy('building_id')
            ->orderBy('room_number')
            ->get();
        
        return view('violations.edit', compact('violation', 'students', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Violation $violation)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'nullable|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:minor,moderate,serious,critical',
            'violation_date' => 'required|date|before_or_equal:today',
            'fine_amount' => 'required|numeric|min:0',
            'fine_status' => 'required|in:unpaid,paid,waived',
            'status' => 'required|in:pending,confirmed,appealed,resolved',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|max:5120',
            'remove_evidence' => 'nullable|array'
        ]);
        
        // Handle evidence removal
        $currentEvidence = $violation->evidence ?? [];
        if ($request->filled('remove_evidence')) {
            foreach ($request->remove_evidence as $indexToRemove) {
                if (isset($currentEvidence[$indexToRemove])) {
                    Storage::disk('public')->delete($currentEvidence[$indexToRemove]['path']);
                    unset($currentEvidence[$indexToRemove]);
                }
            }
            $currentEvidence = array_values($currentEvidence);
        }
        
        // Upload new evidence
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('violations', 'public');
                $currentEvidence[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }
        
        $validated['evidence'] = $currentEvidence;
        
        $violation->update($validated);
        
        return redirect()->route('violations.show', $violation)
            ->with('success', 'Vi phạm đã được cập nhật.');
    }

    /**
     * Resolve violation
     */
    public function resolve(Request $request, Violation $violation)
    {
        $validated = $request->validate([
            'resolution_notes' => 'nullable|string'
        ]);
        
        $violation->update([
            'status' => 'resolved',
            'notes' => ($violation->notes ?? '') . '\n\nGiải quyết: ' . ($validated['resolution_notes'] ?? 'Không có ghi chú')
        ]);
        
        return redirect()->route('violations.show', $violation)
            ->with('success', 'Vi phạm đã được giải quyết.');
    }

    /**
     * Mark fine as paid
     */
    public function markPaid(Violation $violation)
    {
        $violation->update([
            'fine_status' => 'paid',
            'fine_paid_date' => now()
        ]);
        
        return redirect()->route('violations.show', $violation)
            ->with('success', 'Đã xác nhận sinh viên đã đóng tiền phạt.');
    }

    /**
     * Waive fine
     */
    public function waive(Violation $violation)
    {
        $violation->update([
            'fine_status' => 'waived'
        ]);
        
        return redirect()->route('violations.show', $violation)
            ->with('success', 'Đã miễn phạt cho sinh viên.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Violation $violation)
    {
        if ($violation->status !== 'pending') {
            return redirect()->route('violations.index')
                ->with('error', 'Chỉ có thể xóa vi phạm đang chờ xử lý.');
        }
        
        // Delete evidence files
        if ($violation->evidence) {
            foreach ($violation->evidence as $evidence) {
                Storage::disk('public')->delete($evidence['path']);
            }
        }
        
        $violation->delete();
        
        return redirect()->route('violations.index')
            ->with('success', 'Vi phạm đã được xóa.');
    }
}
