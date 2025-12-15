<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['room.building', 'student.user', 'assignedToUser']);
        
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
        
        // Filter by room
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        
        // Search by request code or title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('request_code', 'like', '%' . $search . '%')
                  ->orWhere('title', 'like', '%' . $search . '%');
            });
        }
        
        $maintenanceRequests = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('maintenance.index', compact('maintenanceRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $student = $user->student;
        
        // Get student's current room
        $currentRoom = null;
        if ($student) {
            $currentContract = $student->contracts()
                ->where('status', 'active')
                ->with('room.building')
                ->first();
            $currentRoom = $currentContract?->room;
        }
        
        // Admin/Manager can see all rooms
        $rooms = Room::with('building.dormitory')
            ->where('is_active', true)
            ->orderBy('building_id')
            ->orderBy('room_number')
            ->get();
        
        return view('maintenance.create', compact('currentRoom', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                // align with migration enum
                'category' => 'required|in:plumbing,electrical,furniture,air_conditioning,door_lock,window,other',
                'priority' => 'required|in:low,medium,high,urgent',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB per image
            ]);
            
            $user = Auth::user();
            
            // Generate request code
            $requestCode = 'MR' . date('Ymd') . str_pad(MaintenanceRequest::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Resolve student_id
            $studentId = null;
            
            // If user is a student, use their student_id
            if ($user->isStudent() || $user->role === 'student') {
                $student = $user->student;
                if ($student) {
                    $studentId = $student->id;
                } else {
                    return back()->withErrors(['error' => 'Không tìm thấy thông tin sinh viên của bạn. Vui lòng liên hệ quản trị viên.'])->withInput();
                }
            } 
            // If user is admin, try to get student from room contract
            elseif ($user->isAdmin()) {
                $studentId = DB::table('contracts')
                    ->where('room_id', $validated['room_id'])
                    ->where('status', 'active')
                    ->orderByDesc('start_date')
                    ->value('student_id');
                
                if (!$studentId) {
                    return back()->withErrors(['error' => 'Không xác định được sinh viên cho yêu cầu này. Vui lòng gán phòng đang có hợp đồng hoạt động.'])->withInput();
                }
            } 
            // If user is neither student nor admin
            else {
                return back()->withErrors(['error' => 'Bạn không có quyền tạo yêu cầu bảo trì.'])->withInput();
            }

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('maintenance', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Create request
            $maintenanceRequest = MaintenanceRequest::create([
                'request_code' => $requestCode,
                'room_id' => $validated['room_id'],
                'student_id' => $studentId,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'status' => 'pending',
                'requested_at' => now(),
                'images' => !empty($imagePaths) ? $imagePaths : null,
            ]);
            
            return redirect()->route('maintenance.show', $maintenanceRequest)
                ->with('success', 'Yêu cầu bảo trì đã được gửi thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceRequest $maintenanceRequest)
    {
        // Load relationships - đảm bảo student.user được load
        $maintenanceRequest->loadMissing([
            'room.building.dormitory',
            'student.user',
            'assignedToUser'
        ]);
        
        // Nếu student chưa được load nhưng có student_id, load lại
        if (!$maintenanceRequest->relationLoaded('student') && $maintenanceRequest->student_id) {
            $student = \App\Models\Student::with('user')->find($maintenanceRequest->student_id);
            if ($student) {
                $maintenanceRequest->setRelation('student', $student);
            }
        }
        
        return view('maintenance.show', compact('maintenanceRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền: sinh viên chỉ được sửa yêu cầu của mình
        if (!$user->isAdmin()) {
            $student = $user->student;
            if (!$student || $maintenanceRequest->student_id != $student->id) {
                return redirect()->route('students.my-maintenance')
                    ->with('error', 'Bạn không có quyền chỉnh sửa yêu cầu này.');
            }
        }
        
        // Chỉ cho phép sửa khi trạng thái là pending hoặc rejected
        if (!in_array($maintenanceRequest->status, ['pending', 'rejected'])) {
            $redirectRoute = $user->isAdmin() ? 'maintenance.show' : 'students.my-maintenance';
            return redirect()->route($redirectRoute, $user->isAdmin() ? $maintenanceRequest : [])
                ->with('error', 'Không thể chỉnh sửa yêu cầu đã được duyệt, đang xử lý hoặc đã hoàn thành.');
        }
        
        $rooms = Room::with('building.dormitory')
            ->where('is_active', true)
            ->orderBy('building_id')
            ->orderBy('room_number')
            ->get();
        
        return view('maintenance.edit', compact('maintenanceRequest', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền: sinh viên chỉ được sửa yêu cầu của mình
        if (!$user->isAdmin()) {
            $student = $user->student;
            if (!$student || $maintenanceRequest->student_id != $student->id) {
                return redirect()->route('students.my-maintenance')
                    ->with('error', 'Bạn không có quyền chỉnh sửa yêu cầu này.');
            }
        }
        
        // Chỉ cho phép sửa khi trạng thái là pending hoặc rejected
        if (!in_array($maintenanceRequest->status, ['pending', 'rejected'])) {
            $redirectRoute = $user->isAdmin() ? 'maintenance.show' : 'students.my-maintenance';
            return redirect()->route($redirectRoute, $user->isAdmin() ? $maintenanceRequest : [])
                ->with('error', 'Không thể chỉnh sửa yêu cầu đã được duyệt, đang xử lý hoặc đã hoàn thành.');
        }
        
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:plumbing,electrical,furniture,air_conditioning,door_lock,window,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);
        
        // Không cho phép thay đổi trạng thái qua form edit thông thường
        $maintenanceRequest->update($validated);
        
        // Redirect về trang phù hợp
        $redirectRoute = $user->isAdmin() ? 'maintenance.show' : 'students.my-maintenance';
        return redirect()->route($redirectRoute, $user->isAdmin() ? $maintenanceRequest : [])
            ->with('success', 'Yêu cầu bảo trì đã được cập nhật.');
    }

    /**
     * Approve maintenance request
     */
    public function approve(MaintenanceRequest $maintenance)
    {
        if ($maintenance->status !== 'pending') {
            return redirect()->route('maintenance.show', $maintenance)
                ->with('error', 'Chỉ có thể duyệt yêu cầu đang chờ duyệt.');
        }

        $maintenance->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Yêu cầu đã được duyệt.');
    }

    /**
     * Reject maintenance request
     */
    public function reject(Request $request, MaintenanceRequest $maintenance)
    {
        if ($maintenance->status !== 'pending') {
            return redirect()->route('maintenance.show', $maintenance)
                ->with('error', 'Chỉ có thể từ chối yêu cầu đang chờ duyệt.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $maintenance->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Yêu cầu đã bị từ chối.');
    }

    /**
     * Start processing maintenance request
     */
    public function start(MaintenanceRequest $maintenance)
    {
        if ($maintenance->status !== 'approved') {
            return redirect()->route('maintenance.show', $maintenance)
                ->with('error', 'Chỉ có thể bắt đầu xử lý yêu cầu đã được duyệt.');
        }

        $maintenance->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Đã bắt đầu xử lý yêu cầu.');
    }

    /**
     * Assign maintenance request to user
     */
    public function assign(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'scheduled_date' => 'nullable|date|after_or_equal:today'
        ]);
        
        $maintenanceRequest->update([
            'assigned_to' => $validated['assigned_to'],
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'status' => 'approved'
        ]);
        
        return redirect()->route('maintenance.show', $maintenanceRequest)
            ->with('success', 'Yêu cầu đã được phân công.');
    }

    /**
     * Mark maintenance request as processed
     */
    public function process(MaintenanceRequest $maintenanceRequest)
    {
        if ($maintenanceRequest->status !== 'in_progress') {
            return redirect()->route('maintenance.show', $maintenanceRequest)
                ->with('error', 'Chỉ có thể đánh dấu đã xử lý cho yêu cầu đang xử lý.');
        }

        $maintenanceRequest->update([
            'status' => 'processed',
        ]);
        
        return redirect()->route('maintenance.show', $maintenanceRequest)
            ->with('success', 'Yêu cầu đã được đánh dấu là đã xử lý.');
    }

    /**
     * Mark maintenance request as complete
     */
    public function complete(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        if (!in_array($maintenanceRequest->status, ['in_progress', 'processed'])) {
            return redirect()->route('maintenance.show', $maintenanceRequest)
                ->with('error', 'Chỉ có thể hoàn thành yêu cầu đang xử lý hoặc đã xử lý.');
        }

        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
            'cost' => 'nullable|numeric|min:0',
        ]);
        
        $maintenanceRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $validated['completion_notes'] ?? null,
            'cost' => $validated['cost'] ?? null,
        ]);
        
        return redirect()->route('maintenance.show', $maintenanceRequest)
            ->with('success', 'Yêu cầu bảo trì đã hoàn thành.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền: sinh viên chỉ được xóa yêu cầu của chính mình tạo ra
        if (!$user->isAdmin()) {
            $student = $user->student;
            
            // Kiểm tra sinh viên có tồn tại không
            if (!$student) {
                return redirect()->route('students.my-maintenance')
                    ->with('error', 'Không tìm thấy thông tin sinh viên.');
            }
            
            // Kiểm tra yêu cầu có phải do sinh viên này tạo không
            if ($maintenanceRequest->student_id != $student->id) {
                return redirect()->route('students.my-maintenance')
                    ->with('error', 'Bạn không thể xóa yêu cầu do người khác tạo.');
            }
            
            // Sinh viên chỉ xóa được yêu cầu pending hoặc rejected
            if (!in_array($maintenanceRequest->status, ['pending', 'rejected'])) {
                return redirect()->route('students.my-maintenance')
                    ->with('error', 'Chỉ có thể xóa yêu cầu đang chờ duyệt hoặc bị từ chối.');
            }
        } else {
            // Admin có thể xóa yêu cầu pending, rejected, cancelled
            if (!in_array($maintenanceRequest->status, ['pending', 'rejected', 'cancelled'])) {
                return redirect()->route('maintenance.index')
                    ->with('error', 'Không thể xóa yêu cầu đang xử lý hoặc đã hoàn thành.');
            }
        }
        
        // Xóa hình ảnh nếu có
        if ($maintenanceRequest->images) {
            foreach ($maintenanceRequest->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $maintenanceRequest->delete();
        
        $redirectRoute = $user->isAdmin() ? 'maintenance.index' : 'students.my-maintenance';
        return redirect()->route($redirectRoute)
            ->with('success', 'Yêu cầu bảo trì đã được xóa.');
    }
}
