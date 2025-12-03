<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Task;
use App\Models\Participant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalTask = Task::count();
        $totalParticipant = Participant::count();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalTask',
            'totalParticipant'
        ));
    }
}
