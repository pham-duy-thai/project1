<?php

namespace App\Http\Controllers;

use App\Models\RoomRegistration;
use App\Models\Room;
use Illuminate\Http\Request;

class AdminRoomRegistrationController extends Controller
{
    // List đơn đăng ký
    public function index()
    {
        $registrations = RoomRegistration::with(['student', 'room.building'])
            ->latest()
            ->get();

        return view('admin.registrations.index', compact('registrations'));
    }

    // Chi tiết đăng ký
    public function show(RoomRegistration $registration)
    {
        return view('admin.registrations.show', compact('registration'));
    }

    // Duyệt
    public function approve(RoomRegistration $registration)
    {
        $registration->update(['status' => 'approved']);

        // Giảm số lượng chỗ trống của phòng
        $registration->room->decrement('capacity');

        return back()->with('success', 'Đã duyệt yêu cầu!');
    }

    // Từ chối
    public function reject(RoomRegistration $registration)
    {
        $registration->update(['status' => 'rejected']);

        return back()->with('error', 'Đã từ chối yêu cầu!');
    }
}
