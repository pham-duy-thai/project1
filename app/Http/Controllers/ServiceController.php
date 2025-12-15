<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Service::query();

        if (request('type')) {
            $query->where('type', request('type'));
        }

        if (request('search')) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.request('search').'%')
                  ->orWhere('description', 'like', '%'.request('search').'%');
            });
        }

        // For admin, show all. For students, show only active
        if (Auth::user()->isStudent()) {
            $query->where('is_active', true);
        }

        $services = $query->orderBy('name')->paginate(12);

        // For students, get their registrations
        $myRegistrations = [];
        if (Auth::user()->isStudent() && Auth::user()->student) {
            $myRegistrations = ServiceRegistration::where('student_id', Auth::user()->student->id)
                ->whereIn('status', ['pending', 'active'])
                ->pluck('service_id')
                ->toArray();
        }

        return view('services.index', compact('services', 'myRegistrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
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
                'requires_approval' => $request->has('requires_approval') ? 1 : 0,
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:monthly,one_time,per_use',
                'price' => 'required|numeric|min:0',
                'unit' => 'nullable|string|max:50',
                'icon' => 'nullable|string|max:100',
                'is_active' => 'required|boolean',
                'requires_approval' => 'required|boolean',
                'max_registrations' => 'nullable|integer|min:1',
            ]);

            Service::create($validated);

            return redirect()->route('services.index')
                ->with('success', 'Tạo dịch vụ thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load('registrations.student.user');

        $myRegistration = null;
        if (Auth::user()->isStudent() && Auth::user()->student) {
            $myRegistration = ServiceRegistration::where('service_id', $service->id)
                ->where('student_id', Auth::user()->student->id)
                ->whereIn('status', ['pending', 'active'])
                ->first();
        }

        return view('services.show', compact('service', 'myRegistration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        try {
            // Handle checkbox values before validation
            $request->merge([
                'is_active' => $request->has('is_active') ? 1 : 0,
                'requires_approval' => $request->has('requires_approval') ? 1 : 0,
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:monthly,one_time,per_use',
                'price' => 'required|numeric|min:0',
                'unit' => 'nullable|string|max:50',
                'icon' => 'nullable|string|max:100',
                'is_active' => 'required|boolean',
                'requires_approval' => 'required|boolean',
                'max_registrations' => 'nullable|integer|min:1',
            ]);

            $service->update($validated);

            return redirect()->route('services.index')
                ->with('success', 'Cập nhật dịch vụ thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Xóa dịch vụ thành công.');
    }

    /**
     * Student registers for a service
     */
    public function register(Request $request, Service $service)
    {
        $user = Auth::user();
        
        if (!$user->isStudent() || !$user->student) {
            return back()->with('error', 'Chỉ sinh viên mới có thể đăng ký dịch vụ.');
        }

        if (!$service->is_active) {
            return back()->with('error', 'Dịch vụ này hiện không khả dụng.');
        }

        if (!$service->isAvailable()) {
            return back()->with('error', 'Dịch vụ đã đạt số lượng đăng ký tối đa.');
        }

        $existing = ServiceRegistration::where('service_id', $service->id)
            ->where('student_id', $user->student->id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Bạn đã đăng ký dịch vụ này rồi.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        ServiceRegistration::create([
            'service_id' => $service->id,
            'student_id' => $user->student->id,
            'start_date' => now(),
            'end_date' => $service->type === 'monthly' ? now()->addMonth() : null,
            'status' => $service->requires_approval ? 'pending' : 'active',
            'notes' => $validated['notes'] ?? null,
            'approved_by' => $service->requires_approval ? null : $user->id,
            'approved_at' => $service->requires_approval ? null : now(),
        ]);

        $message = $service->requires_approval 
            ? 'Đăng ký dịch vụ thành công. Vui lòng chờ duyệt.' 
            : 'Đăng ký dịch vụ thành công.';

        return back()->with('success', $message);
    }

    /**
     * Admin approves a registration
     */
    public function approve(ServiceRegistration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Đăng ký này không ở trạng thái chờ duyệt.');
        }

        $registration->update([
            'status' => 'active',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Duyệt đăng ký thành công.');
    }

    /**
     * Cancel a registration
     */
    public function cancel(ServiceRegistration $registration)
    {
        $user = Auth::user();

        // Admin can cancel any, student can only cancel their own
        if (!$user->isAdmin()) {
            if (!$user->student || $registration->student_id !== $user->student->id) {
                return back()->with('error', 'Bạn không có quyền hủy đăng ký này.');
            }
        }

        if ($registration->status === 'cancelled') {
            return back()->with('error', 'Đăng ký đã được hủy rồi.');
        }

        $registration->update([
            'status' => 'cancelled',
            'end_date' => now(),
        ]);

        return back()->with('success', 'Hủy đăng ký thành công.');
    }

    /**
     * View all registrations for a service (admin only)
     */
    public function registrations(Service $service)
    {
        $registrations = $service->registrations()
            ->with('student.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('services.registrations', compact('service', 'registrations'));
    }

    /**
     * My service registrations (student)
     */
    public function myRegistrations()
    {
        $user = Auth::user();
        
        if (!$user->isStudent() || !$user->student) {
            return redirect()->route('dashboard')
                ->with('error', 'Chỉ sinh viên mới có quyền xem trang này.');
        }

        $registrations = ServiceRegistration::where('student_id', $user->student->id)
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('services.my-registrations', compact('registrations'));
    }
}
