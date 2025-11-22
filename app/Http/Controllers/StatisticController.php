<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Nếu bạn vẫn cần danh sách chi tiết thống kê theo phòng
        $statistics = Statistic::with('room')->get();

        // 1. Tổng số sinh viên đang ở (tổng số người trong tất cả phòng)
        $total_students = Room::sum('current_occupancy');

        // 2. Phòng đang sử dụng (có ít nhất 1 người)
        $occupied_rooms = Room::where('current_occupancy', '>', 0)->count();

        // 3. Phòng trống (không có ai)
        $available_rooms = Room::where('current_occupancy', '=', 0)->count();

        // 4. Doanh thu tháng hiện tại (tổng revenue trong bảng statistics)
        $now = Carbon::now();
        $monthly_revenue = Statistic::where('month', $now->month)
            ->where('year', $now->year)
            ->sum('revenue');

        // 5. Dữ liệu 12 tháng gần nhất cho biểu đồ doanh thu
        $stats = Statistic::selectRaw('year, month, SUM(revenue) as total_revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->reverse(); // đảo lại: tháng cũ trước, tháng mới sau

        $months = $stats->map(function ($item) {
            return sprintf('%02d/%d', $item->month, $item->year);
        })->values();

        $revenues = $stats->pluck('total_revenue')->values();

        return view('admin.statistics.index', [
            'statistics'      => $statistics,
            'total_students'  => $total_students,
            'occupied_rooms'  => $occupied_rooms,
            'available_rooms' => $available_rooms,
            'monthly_revenue' => $monthly_revenue,
            'months'          => $months,
            'revenues'        => $revenues,
        ])->with('layout', 'layout2.theme');
    }

    // các hàm create/store/edit/update/destroy giữ nguyên
}
