<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Room;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceRequest;
use App\Models\Announcement;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin/Manager Dashboard
        if ($user->isAdmin()) {
            // Thống kê tổng quan
            $totalStudents = Student::where('status', 'active')->count();
            $totalRooms = Room::where('is_active', true)->count();
            $occupiedRooms = Room::where('status', 'occupied')->count();
            $availableRooms = Room::where('status', 'available')->count();
            
            // Hợp đồng
            $activeContracts = Contract::where('status', 'active')->count();
            $expiringContracts = Contract::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30))
                ->count();
            
            // Tài chính
            $unpaidInvoices = Invoice::where('status', 'pending')
                ->orWhere('status', 'partial')
                ->count();
            $totalUnpaidAmount = Invoice::where('status', 'pending')
                ->orWhere('status', 'partial')
                ->sum('remaining_amount');
            $monthlyRevenue = Invoice::where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount');
            
            // Bảo trì
            $pendingMaintenance = MaintenanceRequest::where('status', 'pending')
                ->orWhere('status', 'approved')
                ->count();
            $urgentMaintenance = MaintenanceRequest::where('priority', 'urgent')
                ->whereIn('status', ['pending', 'approved', 'in_progress'])
                ->count();
            
            // Dữ liệu gần đây
            $recentStudents = Student::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            $recentMaintenanceRequests = MaintenanceRequest::with(['room.building', 'student'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            $recentAnnouncements = Announcement::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();
            
            // Biểu đồ tỷ lệ lấp đầy phòng
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
            
            return view('pages.dashboard', compact(
                'totalStudents',
                'totalRooms',
                'occupiedRooms',
                'availableRooms',
                'activeContracts',
                'expiringContracts',
                'unpaidInvoices',
                'totalUnpaidAmount',
                'monthlyRevenue',
                'pendingMaintenance',
                'urgentMaintenance',
                'recentStudents',
                'recentMaintenanceRequests',
                'recentAnnouncements',
                'occupancyRate'
            ));
        }
        
        // Student Dashboard
        $student = $user->student;
        if (!$student) {
            return view('pages.dashboard-student', [
                'currentContract' => null,
                'invoices' => collect(),
                'maintenanceRequests' => 0,
                'announcements' => collect(),
                'violations' => 0,
                'unpaidInvoices' => 0
            ]);
        }
        
        // Thông tin phòng hiện tại
        $currentContract = Contract::where('student_id', $student->id)
            ->where('status', 'active')
            ->with(['room.building.dormitory', 'room.roomType', 'room.amenities'])
            ->first();
        
        // Hóa đơn chưa thanh toán
        $invoices = Invoice::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'partial'])
            ->with(['room.building'])
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Thống kê
        $unpaidInvoices = $invoices->count();
        $maintenanceRequests = MaintenanceRequest::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->count();
        $violations = Violation::where('student_id', $student->id)
            ->where('status', 'pending')
            ->count();
        
        // Thông báo mới - filter theo đối tượng
        $announcements = Announcement::where('is_published', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->where(function($query) use ($student) {
                $query->where('target_audience', 'all')
                    ->orWhere('target_audience', 'students')
                    ->orWhere(function($q) use ($student) {
                        $q->where('target_audience', 'building')
                            ->where('target_id', $student->currentContract?->room?->building_id);
                    })
                    ->orWhere(function($q) use ($student) {
                        $q->where('target_audience', 'room')
                            ->where('target_id', $student->currentContract?->room_id);
                    });
            })
            ->orderBy('priority', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('pages.dashboard-student', compact(
            'currentContract',
            'invoices',
            'unpaidInvoices',
            'maintenanceRequests',
            'announcements',
            'violations'
        ));
    }
}
