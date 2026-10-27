<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartmentNote;
use App\Models\UmsTicketSetting;
use App\Models\WorkOrder;
use App\Services\UmsTicketService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkOrderController extends Controller
{
    public function __construct(
        protected UmsTicketService $ticketService
    ) {}

    public function index(Request $request): View
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $departments = ['mep' => 'MEP & Instrument', 'sipil' => 'Building Const'];
        $categories = ['minor' => 'Minor', 'mayor' => 'Mayor', 'project' => 'Project', 'unidentified' => 'Belum Diidentifikasi'];

        $orders = [];
        foreach ($departments as $deptKey => $deptLabel) {
            $orders[$deptKey] = [];
            foreach ($categories as $catKey => $catLabel) {
                $record = WorkOrder::whereDate('date', $selectedDate)
                    ->where('department', $deptKey)
                    ->where('category', $catKey)
                    ->first();

                $orders[$deptKey][$catKey] = [
                    'waiting_schedule' => $record?->waiting_schedule ?? 0,
                    'in_progress' => $record?->in_progress ?? 0,
                    'completed' => $record?->completed ?? 0,
                    'source' => $record?->source ?? 'manual',
                    'last_synced_at' => $record?->last_synced_at,
                ];
            }
        }

        $setting = UmsTicketSetting::first();
        $mepNote = DepartmentNote::whereDate('date', $selectedDate)->where('department', 'mep')->first();
        $sipilNote = DepartmentNote::whereDate('date', $selectedDate)->where('department', 'sipil')->first();

        return view('admin.work_orders.index', compact(
            'selectedDate',
            'departments',
            'categories',
            'orders',
            'setting',
            'mepNote',
            'sipilNote'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'orders' => ['nullable', 'array'],
            'notes' => ['nullable', 'array'],
            'notes.mep' => ['nullable', 'string', 'max:1000'],
            'notes.sipil' => ['nullable', 'string', 'max:1000'],
        ]);

        $date = $validated['date'];

        if (! empty($validated['orders'])) {
            foreach ($validated['orders'] as $dept => $categories) {
                foreach ($categories as $cat => $counts) {
                    $existing = WorkOrder::whereDate('date', $date)
                        ->where('department', $dept)
                        ->where('category', $cat)
                        ->first();

                    $payload = [
                        'waiting_schedule' => (int) ($counts['waiting_schedule'] ?? 0),
                        'in_progress' => (int) ($counts['in_progress'] ?? 0),
                        'completed' => (int) ($counts['completed'] ?? 0),
                        'source' => 'manual',
                        'updated_by' => Auth::id(),
                    ];

                    if ($existing) {
                        $existing->update($payload);
                    } else {
                        WorkOrder::create(array_merge([
                            'date' => $date,
                            'department' => $dept,
                            'category' => $cat,
                        ], $payload));
                    }
                }
            }
        }

        if (isset($validated['notes'])) {
            foreach (['mep', 'sipil'] as $deptKey) {
                if (array_key_exists($deptKey, $validated['notes'])) {
                    DepartmentNote::updateOrCreate(
                        ['date' => $date, 'department' => $deptKey],
                        ['hot_issue' => $validated['notes'][$deptKey], 'updated_by' => Auth::id()]
                    );
                }
            }
        }

        return redirect()->route('admin.work_orders.index', ['date' => $date])
            ->with('success', 'Data pekerjaan dan Hot Issue MEP & Building Const berhasil diperbarui.');
    }

    public function sync(Request $request): RedirectResponse
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $result = $this->ticketService->syncWorkOrders($date);

        if ($result['success']) {
            return redirect()->route('admin.work_orders.index', ['date' => $date])
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.work_orders.index', ['date' => $date])
            ->with('warning', $result['message']);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'endpoint_url' => ['required', 'url'],
            'api_key' => ['nullable', 'string'],
            'auto_sync' => ['nullable', 'boolean'],
        ]);

        UmsTicketSetting::updateOrCreate(
            ['id' => 1],
            [
                'endpoint_url' => $validated['endpoint_url'],
                'api_key' => $validated['api_key'] ?? null,
                'auto_sync' => $request->boolean('auto_sync'),
            ]
        );

        return back()->with('success', 'Pengaturan integrasi umsticket.artyc.id berhasil disimpan.');
    }
}
