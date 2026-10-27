<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cost;
use App\Models\Dorcat;
use App\Models\Transport;
use App\Models\UmsTicketSetting;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today()->toDateString();

        $pendingUsers = User::where('is_approved', false)->orderBy('created_at', 'desc')->get();
        $totalUsers = User::count();
        $costsCount = Cost::whereDate('date', $today)->count();
        $dorcatCount = Dorcat::whereDate('date', $today)->count();
        $transport = Transport::whereDate('date', $today)->first();
        $mepCount = WorkOrder::whereDate('date', $today)->where('department', 'mep')->sum('waiting_schedule')
            + WorkOrder::whereDate('date', $today)->where('department', 'mep')->sum('in_progress')
            + WorkOrder::whereDate('date', $today)->where('department', 'mep')->sum('completed');
        $setting = UmsTicketSetting::first();

        return view('admin.dashboard', compact(
            'today',
            'pendingUsers',
            'totalUsers',
            'costsCount',
            'dorcatCount',
            'transport',
            'mepCount',
            'setting'
        ));
    }
}
