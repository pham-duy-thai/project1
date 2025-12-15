<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::with(['student.user', 'room.building']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        // Filter expiring soon
        if ($request->filled('expiring_soon')) {
            $query->where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30));
        }
        
        // Search by contract number
        if ($request->filled('search')) {
            $query->where('contract_number', 'like', '%' . $request->search . '%');
        }
        
        $contracts = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show authenticated student's own contracts list.
     */
    public function myContracts(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect()->route('contracts.index');
        }
        $student = $user->student;
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        $query = Contract::with(['room.building', 'room.roomType'])
            ->where('student_id', $student->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contracts = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('contracts.my-contracts', compact('contracts', 'student'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::where('status', 'active')
            ->with('user')
            ->orderBy('student_code')
            ->get();
        
        // Get available rooms
        $availableRooms = Room::where('status', 'available')
            ->where('is_active', true)
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->with(['building.dormitory', 'roomType'])
            ->get();
        
        // Pre-select student if provided
        $selectedStudentId = $request->get('student_id');
        
        return view('contracts.create', compact('students', 'availableRooms', 'selectedStudentId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'monthly_rent' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'payment_cycle' => 'required|in:monthly,semester,yearly',
            'notes' => 'nullable|string'
        ]);
        
        // Check if room is available
        $room = Room::findOrFail($validated['room_id']);
        if ($room->current_occupancy >= $room->capacity) {
            return back()->withErrors([
                'room_id' => 'Phòng đã đầy.'
            ])->withInput();
        }
        
        // Check if student already has active contract
        $hasActiveContract = Contract::where('student_id', $validated['student_id'])
            ->whereIn('status', ['pending', 'active'])
            ->exists();
        
        if ($hasActiveContract) {
            return back()->withErrors([
                'student_id' => 'Sinh viên đã có hợp đồng đang hoạt động hoặc chờ xử lý. Không thể tạo hợp đồng mới.'
            ])->withInput();
        }
        
        DB::beginTransaction();
        try {
            // Generate contract number
            $contractNumber = 'HD' . date('Ymd') . str_pad(Contract::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Calculate total amount
            $months = now()->parse($validated['start_date'])->diffInMonths($validated['end_date']);
            $totalAmount = $validated['monthly_rent'] * $months;
            
            // Create contract
            $contract = Contract::create([
                'contract_number' => $contractNumber,
                'student_id' => $validated['student_id'],
                'room_id' => $validated['room_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'monthly_rent' => $validated['monthly_rent'],
                'deposit_amount' => $validated['deposit_amount'],
                'total_amount' => $totalAmount,
                'payment_cycle' => $validated['payment_cycle'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null
            ]);
            
            DB::commit();
            
            return redirect()->route('contracts.show', $contract)
                ->with('success', 'Hợp đồng đã được tạo thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $user = Auth::user();
        
        // If student, only allow viewing their own contracts
        if ($user->role === 'student') {
            $student = $user->student;
            if (!$student || $contract->student_id !== $student->id) {
                abort(403, 'Bạn không có quyền xem hợp đồng này.');
            }
        }
        
        $contract->load([
            'student.user',
            'room.building.dormitory',
            'room.roomType',
            'invoices',
            'payments'
        ]);
        
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        if ($contract->status === 'active') {
            return redirect()->route('contracts.show', $contract)
                ->with('error', 'Không thể chỉnh sửa hợp đồng đang hoạt động.');
        }
        
        $students = Student::where('status', 'active')
            ->with('user')
            ->orderBy('student_code')
            ->get();
        
        $availableRooms = Room::where('status', 'available')
            ->where('is_active', true)
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->orWhere('id', $contract->room_id)
            ->with(['building.dormitory', 'roomType'])
            ->get();
        
        return view('contracts.edit', compact('contract', 'students', 'availableRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        if ($contract->status === 'active') {
            return redirect()->route('contracts.show', $contract)
                ->with('error', 'Không thể chỉnh sửa hợp đồng đang hoạt động.');
        }
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_rent' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'payment_cycle' => 'required|in:monthly,semester,yearly',
            'notes' => 'nullable|string'
        ]);
        
        // Calculate total amount
        $months = now()->parse($validated['start_date'])->diffInMonths($validated['end_date']);
        $validated['total_amount'] = $validated['monthly_rent'] * $months;
        
        $contract->update($validated);
        
        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Hợp đồng đã được cập nhật.');
    }

    /**
     * Sign the contract
     */
    public function sign(Contract $contract)
    {
        if ($contract->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể phê duyệt hợp đồng đang chờ xử lý.');
        }
        
        $user = Auth::user();
        
        // Only admin/manager can approve contracts
        if (!$user->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền phê duyệt hợp đồng này.');
        }
        
        DB::beginTransaction();
        try {
            // Approve and activate contract
            $contract->signed_by_student = true;
            $contract->signed_by_manager = true;
            $contract->student_signed_at = now();
            $contract->manager_signed_at = now();
            $contract->signed_by = $user->id;
            $contract->signed_at = now();
            $contract->status = 'active';
            
            // Update room occupancy
            $room = $contract->room;
            $room->increment('current_occupancy');
            if ($room->current_occupancy >= $room->capacity) {
                $room->status = 'occupied';
                $room->save();
            }
            
            $contract->save();
            
            DB::commit();
            
            return redirect()->route('contracts.show', $contract)
                ->with('success', 'Hợp đồng đã được phê duyệt và kích hoạt thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * Terminate the contract
     */
    public function terminate(Request $request, Contract $contract)
    {
        if ($contract->status !== 'active') {
            return back()->with('error', 'Chỉ có thể chấm dứt hợp đồng đang hoạt động.');
        }
        
        $validated = $request->validate([
            'reason' => 'required|string',
            'termination_date' => 'required|date|after_or_equal:' . $contract->start_date->format('Y-m-d')
        ]);
        
        DB::beginTransaction();
        try {
            $contract->status = 'terminated';
            $contract->terminated_at = $validated['termination_date'];
            $contract->termination_reason = $validated['reason'];
            $contract->save();
            
            // Update room occupancy
            $room = $contract->room;
            $room->decrement('current_occupancy');
            if ($room->current_occupancy < $room->capacity) {
                $room->status = 'available';
                $room->save();
            }
            
            DB::commit();
            
            return redirect()->route('contracts.show', $contract)
                ->with('success', 'Hợp đồng đã được chấm dứt thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if ($contract->status === 'active') {
            return redirect()->route('contracts.index')
                ->with('error', 'Không thể xóa hợp đồng đang hoạt động.');
        }
        
        // Check if has invoices
        if ($contract->invoices()->exists()) {
            return redirect()->route('contracts.index')
                ->with('error', 'Không thể xóa hợp đồng có hóa đơn.');
        }
        
        $contract->delete();
        
        return redirect()->route('contracts.index')
            ->with('success', 'Hợp đồng đã được xóa.');
    }
    
    /**
     * Show room registration form for students
     */
    public function registerRoom()
    {
        $user = Auth::user();
        
        // Only students can register
        if ($user->role !== 'student') {
            abort(403, 'Chỉ sinh viên mới có thể đăng ký phòng.');
        }
        
        $student = $user->student;
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy thông tin sinh viên.');
        }
        
        // Check if student already has active/pending contract
        $existingContract = Contract::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'active'])
            ->with('room.building')
            ->first();
        
        if ($existingContract) {
            return redirect()->route('contracts.show', $existingContract)
                ->with('error', 'Bạn đã có hợp đồng ' . ($existingContract->status === 'active' ? 'đang hoạt động' : 'đang chờ xử lý') . '. Không thể đăng ký phòng mới.');
        }
        
        // Get available rooms
        $availableRooms = Room::where('status', 'available')
            ->where('is_active', true)
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->with(['building.dormitory', 'roomType'])
            ->get();
        
        return view('contracts.register-room', compact('student', 'availableRooms'));
    }
    
    /**
     * Store student's room registration
     */
    public function storeRegistration(Request $request)
    {
        $user = Auth::user();
        
        // Only students can register
        if ($user->role !== 'student') {
            abort(403, 'Chỉ sinh viên mới có thể đăng ký phòng.');
        }
        
        $student = $user->student;
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Không tìm thấy thông tin sinh viên.');
        }
        
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'payment_cycle' => 'required|in:monthly,semester,yearly',
            'notes' => 'nullable|string'
        ]);
        
        // Check if room is available
        $room = Room::with('roomType')->findOrFail($validated['room_id']);
        if ($room->current_occupancy >= $room->capacity) {
            return back()->withErrors([
                'room_id' => 'Phòng đã đầy.'
            ])->withInput();
        }
        
        // Check if student already has active/pending contract (double check)
        $hasContract = Contract::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();
        
        if ($hasContract) {
            return back()->withErrors([
                'error' => 'Bạn đã có hợp đồng đang hoạt động hoặc chờ xử lý.'
            ])->withInput();
        }
        
        DB::beginTransaction();
        try {
            // Generate contract number
            $contractNumber = 'HD' . date('Ymd') . str_pad(Contract::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Get room price from room type
            $monthlyRent = $room->roomType->price_per_month ?? 0;
            
            // Default: 6 months contract
            $endDate = now()->parse($validated['start_date'])->addMonths(6);
            $months = 6;
            $totalAmount = $monthlyRent * $months;
            $depositAmount = $monthlyRent; // 1 month deposit
            
            // Create contract with pending status
            $contract = Contract::create([
                'contract_number' => $contractNumber,
                'student_id' => $student->id,
                'room_id' => $validated['room_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
                'monthly_rent' => $monthlyRent,
                'deposit_amount' => $depositAmount,
                'total_amount' => $totalAmount,
                'payment_cycle' => $validated['payment_cycle'],
                'status' => 'pending', // Admin needs to approve
                'notes' => $validated['notes'] ?? null
            ]);
            
            DB::commit();
            
            return redirect()->route('contracts.show', $contract)
                ->with('success', 'Đăng ký phòng thành công! Vui lòng đợi quản lý phê duyệt.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
 
