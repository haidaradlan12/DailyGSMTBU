<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cost;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CostController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $categories = [
            'tiket' => 'Cost Tiket - 2351',
            'npe' => 'Cost NPE - 2402',
            'office' => 'Office Expense - 2403',
            'mess' => 'Mess Expense - 2405',
            'building' => 'Cost Building Maint - 2410',
        ];

        $costs = [];
        foreach ($categories as $cat => $label) {
            $record = Cost::whereDate('date', $selectedDate)->where('category', $cat)->first();
            $costs[$cat] = [
                'label' => $label,
                'plan_amount' => $record ? (float) $record->plan_amount : 0,
                'actual_amount' => $record ? (float) $record->actual_amount : 0,
                'notes' => $record?->notes ?? '',
            ];
        }

        return view('admin.costs.index', compact('selectedDate', 'costs', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'costs' => ['required', 'array'],
            'costs.*.plan_amount' => ['required', 'numeric', 'min:0'],
            'costs.*.actual_amount' => ['required', 'numeric', 'min:0'],
            'costs.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        $date = $validated['date'];

        foreach ($validated['costs'] as $category => $values) {
            $cost = Cost::whereDate('date', $date)->where('category', $category)->first();
            $payload = [
                'plan_amount' => $values['plan_amount'],
                'actual_amount' => $values['actual_amount'],
                'notes' => $values['notes'] ?? null,
                'updated_by' => Auth::id(),
            ];

            if ($cost) {
                $cost->update($payload);
            } else {
                Cost::create(array_merge(['date' => $date, 'category' => $category], $payload));
            }
        }

        return redirect()->route('admin.costs.index', ['date' => $date])
            ->with('success', 'Data Realisasi Cost untuk tanggal ' . $date . ' berhasil disimpan!');
    }
}
