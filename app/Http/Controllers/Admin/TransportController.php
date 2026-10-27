<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransportController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $transport = Transport::whereDate('date', $selectedDate)->first();

        return view('admin.transport.index', compact('selectedDate', 'transport'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'pa_bus' => ['required', 'numeric', 'between:0,100'],
            'pa_lv' => ['required', 'numeric', 'between:0,100'],
            'coverage_pengantaran' => ['required', 'numeric', 'between:0,100'],
            'fuel_ratio_bus' => ['nullable', 'numeric', 'min:0'],
            'fuel_ratio_lv' => ['nullable', 'numeric', 'min:0'],
            'atr_lv' => ['nullable', 'numeric', 'between:0,100'],
            'unit_ready_lv' => ['nullable', 'integer', 'min:0'],
            'unit_breakdown_lv' => ['nullable', 'integer', 'min:0'],
            'unit_ready_bus' => ['nullable', 'integer', 'min:0'],
            'unit_breakdown_bus' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transport = Transport::whereDate('date', $validated['date'])->first();
        if ($transport) {
            $transport->update(array_merge($validated, ['updated_by' => Auth::id()]));
        } else {
            Transport::create(array_merge($validated, ['updated_by' => Auth::id()]));
        }

        return redirect()->route('admin.transport.index', ['date' => $validated['date']])
            ->with('success', 'Data metrik Transport (PA Bus, PA LV, Coverage Pengantaran) berhasil disimpan!');
    }
}
