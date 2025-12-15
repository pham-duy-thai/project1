<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('user');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by faculty
        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }
        
        // Filter by class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        
        // Search by name, student code, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('student_code', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        $students = $query->orderBy('student_code')
            ->paginate(20);
        
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // User data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Student data
            'student_code' => 'required|string|max:50|unique:students,student_code',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'citizen_id' => 'required|string|max:20|unique:students,citizen_id',
            'permanent_address' => 'required|string',
            'contact_address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'class' => 'required|string|max:50',
            'academic_year' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'status' => 'required|in:active,inactive,graduated,suspended'
        ]);
        
        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'is_active' => true
            ]);
            
            // Create student profile
            $student = Student::create([
                'user_id' => $user->id,
                'student_code' => $validated['student_code'],
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'citizen_id' => $validated['citizen_id'],
                'permanent_address' => $validated['permanent_address'],
                'contact_address' => $validated['contact_address'] ?? null,
                'parent_name' => $validated['parent_name'] ?? null,
                'parent_phone' => $validated['parent_phone'] ?? null,
                'faculty' => $validated['faculty'],
                'major' => $validated['major'],
                'class' => $validated['class'],
                'academic_year' => $validated['academic_year'] ?? date('Y'),
                'status' => $validated['status']
            ]);
            
            DB::commit();
            
            return redirect()->route('students.show', $student)
                ->with('success', 'Sinh viên đã được tạo thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('user');
        
        // Current contract
        $currentContract = $student->contracts()
            ->where('status', 'active')
            ->with(['room.building.dormitory', 'room.roomType'])
            ->first();
        
        // Contract history
        $contractHistory = $student->contracts()
            ->with(['room.building'])
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Invoices
        $invoices = $student->invoices()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Maintenance requests
        $maintenanceRequests = $student->maintenanceRequests()
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Violations
        $violations = $student->violations()
            ->orderBy('violation_date', 'desc')
            ->get();
        
        // Statistics
        $unpaidInvoicesCount = $student->invoices()
            ->whereIn('status', ['pending', 'overdue'])
            ->count();
        
        $totalDebt = $student->invoices()
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('total_amount');
        
        $maintenanceRequestsCount = $student->maintenanceRequests()->count();
        $violationsCount = $violations->count();
        
        return view('students.show', compact(
            'student',
            'currentContract',
            'contractHistory',
            'invoices',
            'maintenanceRequests',
            'violations',
            'unpaidInvoicesCount',
            'totalDebt',
            'maintenanceRequestsCount',
            'violationsCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student->load('user');
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            // User data
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            
            // Student data
            'student_code' => ['required', 'string', 'max:50', Rule::unique('students')->ignore($student->id)],
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'citizen_id' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student->id)],
            'permanent_address' => 'required|string',
            'contact_address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'class' => 'required|string|max:50',
            'academic_year' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'status' => 'required|in:active,inactive,graduated,suspended'
        ]);
        
        DB::beginTransaction();
        try {
            // Update user account
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email']
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $student->user->update($userData);
            
            // Update student profile
            $student->update([
                'student_code' => $validated['student_code'],
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'citizen_id' => $validated['citizen_id'],
                'permanent_address' => $validated['permanent_address'],
                'contact_address' => $validated['contact_address'] ?? null,
                'parent_name' => $validated['parent_name'] ?? null,
                'parent_phone' => $validated['parent_phone'] ?? null,
                'faculty' => $validated['faculty'],
                'major' => $validated['major'],
                'class' => $validated['class'],
                'academic_year' => $validated['academic_year'] ?? date('Y'),
                'status' => $validated['status']
            ]);
            
            DB::commit();
            
            return redirect()->route('students.show', $student)
                ->with('success', 'Thông tin sinh viên đã được cập nhật.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Check if student has active contracts
        if ($student->contracts()->where('status', 'active')->exists()) {
            return redirect()->route('students.index')
                ->with('error', 'Không thể xóa sinh viên vì còn hợp đồng đang hoạt động.');
        }
        
        DB::beginTransaction();
        try {
            $user = $student->user;
            $student->delete();
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('students.index')
                ->with('success', 'Sinh viên đã được xóa thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('students.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display student's room information
     */
    public function myRoom()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }

        // Get active contract with room info & all related contracts (roommates will be filtered later)
        $contract = $student->contracts()
            ->with([
                'room.building.dormitory',
                'room.roomType',
                'room.contracts.student.user'
            ])
            ->where('status', 'active')
            ->first();

        if (!$contract) {
            return view('students.my-room', [
                'student' => $student,
                'contract' => null,
                'room' => null,
                'roommates' => []
            ]);
        }

        $room = $contract->room;
        
        // Get roommates (other students in same room with active contracts)
        $roommates = $room->contracts()
            ->where('status', 'active')
            ->where('student_id', '!=', $student->id)
            ->with('student.user')
            ->get()
            ->pluck('student');

        return view('students.my-room', compact('student', 'contract', 'room', 'roommates'));
    }

    /**
     * Display student's own profile
     */
    public function myProfile()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }

        // Load student with user relationship
        $student->load('user');

        // Current contract
        $currentContract = $student->contracts()
            ->where('status', 'active')
            ->with(['room.building.dormitory', 'room.roomType'])
            ->first();

        // Contract history
        $contractHistory = $student->contracts()
            ->with(['room.building'])
            ->orderBy('start_date', 'desc')
            ->get();

        // Recent invoices
        $invoices = $student->invoices()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent maintenance requests
        $maintenanceRequests = $student->maintenanceRequests()
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent violations
        $violations = $student->violations()
            ->orderBy('violation_date', 'desc')
            ->limit(5)
            ->get();

        // Statistics
        $unpaidInvoicesCount = $student->invoices()
            ->whereIn('status', ['pending', 'overdue'])
            ->count();

        $totalDebt = $student->invoices()
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('remaining_amount');

        $maintenanceRequestsCount = $student->maintenanceRequests()->count();
        $violationsCount = $student->violations()->count();

        return view('students.my-profile', compact(
            'student',
            'currentContract',
            'contractHistory',
            'invoices',
            'maintenanceRequests',
            'violations',
            'unpaidInvoicesCount',
            'totalDebt',
            'maintenanceRequestsCount',
            'violationsCount'
        ));
    }

    /**
     * Display student's own invoices
     */
    public function myInvoices(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }

        $query = Invoice::where('student_id', $student->id)
            ->with(['contract.room.building']);

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistics
        $stats = [
            'total_unpaid' => Invoice::where('student_id', $student->id)
                ->whereIn('status', ['pending', 'partial'])
                ->sum('remaining_amount'),
            'overdue_count' => Invoice::where('student_id', $student->id)
                ->where('status', '!=', 'paid')
                ->where('due_date', '<', now())
                ->count(),
            'this_month' => Invoice::where('student_id', $student->id)
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount')
        ];

        return view('students.my-invoices', compact('invoices', 'stats'));
    }

    /**
     * Display student's own payments
     */
    public function myPayments(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }

        $query = Payment::where('student_id', $student->id)
            ->with(['invoice.contract.room.building']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistics
        $stats = [
            'total_paid' => Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_count' => Payment::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count(),
            'this_month' => Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->whereMonth('payment_date', now()->month)
                ->sum('amount')
        ];

        return view('students.my-payments', compact('payments', 'stats'));
    }

    /**
     * Display student's own maintenance requests
     */
    public function myMaintenance(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Bạn chưa được đăng ký là sinh viên.');
        }

        $query = MaintenanceRequest::where('student_id', $student->id)
            ->with(['room.building', 'assignedToUser']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $maintenanceRequests = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistics
        $stats = [
            'pending_count' => MaintenanceRequest::where('student_id', $student->id)
                ->whereIn('status', ['pending', 'approved'])
                ->count(),
            'completed_count' => MaintenanceRequest::where('student_id', $student->id)
                ->where('status', 'completed')
                ->count(),
            'total_count' => MaintenanceRequest::where('student_id', $student->id)->count()
        ];

        return view('students.my-maintenance', compact('maintenanceRequests', 'stats'));
    }
}
