<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomRegistration;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $statistics = [];

        foreach (Room::with('building')->get() as $room) {

            // Tổng sinh viên đăng ký trong tháng
            $totalStudents = RoomRegistration::where('room_id', $room->id)
                ->whereMonth('registration_date', $month)
                ->whereYear('registration_date', $year)
                ->count();

            // Doanh thu = số SV * giá phòng
            $revenue = $totalStudents * ($room->price ?? 0);

            $statistics[] = [
                'room_name' => $room->room_number,
                'building'  => $room->building->name ?? 'Không có',
                'month' => $month,
                'year'  => $year,
                'total_students' => $totalStudents,
                'revenue' => $revenue,
            ];
        }

        return view('admin.statistics.index', compact('statistics', 'month', 'year'));
    }
}
