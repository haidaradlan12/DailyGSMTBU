<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\DepartmentNote;
use App\Models\Dorcat;
use App\Models\Transport;
use App\Models\UmsTicketSetting;
use App\Models\WorkOrder;
use App\Services\UmsTicketService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected UmsTicketService $ticketService
    ) {}

    /**
     * Display the main public executive dashboard.
     */
    public function index(Request $request): View
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Auto sync with umsticket.artyc.id if viewing today and last sync is older than 2 minutes
        if ($selectedDate === Carbon::today()->toDateString()) {
            $setting = UmsTicketSetting::first();
            $shouldSync = ! $setting || ! $setting->last_synced_at || $setting->last_synced_at->diffInMinutes(now()) >= 2;
            if ($shouldSync) {
                $this->ticketService->syncWorkOrders($selectedDate);
            }
        }

        $data = $this->collectDashboardData($selectedDate);

        return view('dashboard', $data);
    }

    /**
     * API endpoint for AJAX polling / live data updates.
     */
    public function apiData(Request $request): JsonResponse
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        if ($selectedDate === Carbon::today()->toDateString()) {
            $setting = UmsTicketSetting::first();
            if (! $setting || ! $setting->last_synced_at || $setting->last_synced_at->diffInMinutes(now()) >= 2) {
                $this->ticketService->syncWorkOrders($selectedDate);
            }
        }

        $data = $this->collectDashboardData($selectedDate);

        return response()->json($data);
    }

    /**
     * Collect and prepare all dashboard dataset for a specific date.
     */
    protected function collectDashboardData(string $date): array
    {
        // 1. Costs Data for 5 Categories
        $categories = [
            'tiket' => 'Cost Tiket - 2351',
            'npe' => 'Cost NPE - 2402',
            'office' => 'Office Expense - 2403',
            'mess' => 'Mess Expense - 2405',
            'building' => 'Cost Building Maint - 2410',
        ];

        $costs = [];
        $totalPlan = 0;
        $totalActual = 0;

        foreach ($categories as $key => $label) {
            $record = Cost::whereDate('date', $date)->where('category', $key)->first();
            $plan = $record ? (float) $record->plan_amount : 0;
            $actual = $record ? (float) $record->actual_amount : 0;
            $percentage = $plan > 0 ? round(($actual / $plan) * 100, 1) : 0;
            $remaining = $plan - $actual;

            $totalPlan += $plan;
            $totalActual += $actual;

            $costs[$key] = [
                'key' => $key,
                'label' => $label,
                'plan' => $plan,
                'actual' => $actual,
                'percentage' => $percentage,
                'remaining' => $remaining,
                'notes' => $record?->notes ?? '',
            ];
        }

        // 2. Dorcat (Hot Issues and Progress %)
        $dorcatIssues = Dorcat::whereDate('date', $date)
            ->orderByRaw("CASE WHEN status = 'in_progress' THEN 1 WHEN status = 'open' THEN 2 ELSE 3 END")
            ->orderBy('percentage', 'desc')
            ->get();

        // If today has no records, fallback to the latest available dorcat records
        if ($dorcatIssues->isEmpty()) {
            $latestDate = Dorcat::max('date');
            if ($latestDate) {
                $dorcatIssues = Dorcat::whereDate('date', $latestDate)
                    ->orderByRaw("CASE WHEN status = 'in_progress' THEN 1 WHEN status = 'open' THEN 2 ELSE 3 END")
                    ->get();
            }
        }

        $dorcatStats = [
            'total' => $dorcatIssues->count(),
            'avg_percentage' => $dorcatIssues->count() > 0 ? round($dorcatIssues->avg('percentage'), 1) : 0,
            'open_count' => $dorcatIssues->where('status', 'open')->count(),
            'progress_count' => $dorcatIssues->where('status', 'in_progress')->count(),
            'resolved_count' => $dorcatIssues->where('status', 'resolved')->count(),
        ];

        // 3. Transport (PA LV, ATR LV, PA Bus)
        $transport = Transport::whereDate('date', $date)->first();
        if (! $transport) {
            $latestTransportDate = Transport::max('date');
            if ($latestTransportDate) {
                $transport = Transport::whereDate('date', $latestTransportDate)->first();
            }
        }

        // 4. MEP Instrument & 5. Sipil Work Orders
        $mepOrders = $this->formatWorkOrderMatrix('mep', $date);
        $sipilOrders = $this->formatWorkOrderMatrix('sipil', $date);

        // 6. Hot Issues for MEP & Building Const (Sipil)
        $mepNote = DepartmentNote::whereDate('date', $date)->where('department', 'mep')->first();
        if (! $mepNote) {
            $latestMepDate = DepartmentNote::where('department', 'mep')->whereNotNull('hot_issue')->max('date');
            if ($latestMepDate) {
                $mepNote = DepartmentNote::whereDate('date', $latestMepDate)->where('department', 'mep')->first();
            }
        }

        $sipilNote = DepartmentNote::whereDate('date', $date)->where('department', 'sipil')->first();
        if (! $sipilNote) {
            $latestSipilDate = DepartmentNote::where('department', 'sipil')->whereNotNull('hot_issue')->max('date');
            if ($latestSipilDate) {
                $sipilNote = DepartmentNote::whereDate('date', $latestSipilDate)->where('department', 'sipil')->first();
            }
        }

        // 7. Settings & Sync status
        $setting = UmsTicketSetting::first();

        return [
            'selectedDate' => $date,
            'costs' => $costs,
            'totalCostPlan' => $totalPlan,
            'totalCostActual' => $totalActual,
            'totalCostPercentage' => $totalPlan > 0 ? round(($totalActual / $totalPlan) * 100, 1) : 0,
            'dorcatIssues' => $dorcatIssues,
            'dorcatStats' => $dorcatStats,
            'transport' => $transport,
            'mepOrders' => $mepOrders,
            'sipilOrders' => $sipilOrders,
            'mepHotIssue' => $mepNote?->hot_issue,
            'sipilHotIssue' => $sipilNote?->hot_issue,
            'setting' => $setting,
            'isConnected' => (bool) ($setting && $setting->last_synced_at && $setting->last_sync_status === 'success'),
        ];
    }

    /**
     * Format matrix for MEP / Sipil departments.
     */
    protected function formatWorkOrderMatrix(string $department, string $date): array
    {
        $types = [
            'minor' => 'Minor',
            'mayor' => 'Mayor',
            'project' => 'Project',
            'unidentified' => 'Belum Diidentifikasi',
        ];
        $matrix = [];
        $totalWaiting = 0;
        $totalInProgress = 0;
        $totalCompleted = 0;

        foreach ($types as $type => $label) {
            $record = WorkOrder::whereDate('date', $date)
                ->where('department', $department)
                ->where('category', $type)
                ->first();

            // Fallback to latest date if not found for this date
            if (! $record) {
                $latestDate = WorkOrder::where('department', $department)->where('category', $type)->max('date');
                if ($latestDate) {
                    $record = WorkOrder::whereDate('date', $latestDate)
                        ->where('department', $department)
                        ->where('category', $type)
                        ->first();
                }
            }

            $waiting = $record?->waiting_schedule ?? 0;
            $inProgress = $record?->in_progress ?? 0;
            $completed = $record?->completed ?? 0;
            $rowTotal = $waiting + $inProgress + $completed;

            $totalWaiting += $waiting;
            $totalInProgress += $inProgress;
            $totalCompleted += $completed;

            $matrix[$type] = [
                'label' => $label,
                'waiting_schedule' => $waiting,
                'in_progress' => $inProgress,
                'completed' => $completed,
                'total' => $rowTotal,
                'last_synced_at' => $record?->last_synced_at?->diffForHumans() ?? 'Belum ada data',
            ];
        }

        return [
            'department' => $department,
            'label' => $department === 'mep' ? 'MEP & Instrument' : 'Building Const',
            'rows' => $matrix,
            'totals' => [
                'waiting_schedule' => $totalWaiting,
                'in_progress' => $totalInProgress,
                'completed' => $totalCompleted,
                'overall' => $totalWaiting + $totalInProgress + $totalCompleted,
            ],
        ];
    }
}
