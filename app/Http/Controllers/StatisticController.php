<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use App\Models\Contract;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalRooms = Room::count();
        $activeContracts = Contract::where('status', 'active')->count();
        $availableRooms = Room::whereRaw('id NOT IN (SELECT room_id FROM contracts WHERE status = "active")')->count();

        return view('statistics.index', compact(
            'totalStudents',
            'totalRooms',
            'activeContracts',
            'availableRooms'
        ));
    }
}
