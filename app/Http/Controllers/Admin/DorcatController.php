<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dorcat;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DorcatController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $issues = Dorcat::whereDate('date', $selectedDate)
            ->orderBy('percentage', 'desc')
            ->get();

        return view('admin.dorcat.index', compact('selectedDate', 'issues'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'issue_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'percentage' => ['required', 'integer', 'between:0,100'],
            'status' => ['required', 'in:open,in_progress,resolved'],
            'priority' => ['required', 'in:normal,high,critical'],
            'pic_name' => ['nullable', 'string', 'max:100'],
        ]);

        Dorcat::create(array_merge($validated, ['updated_by' => Auth::id()]));

        return redirect()->route('admin.dorcat.index', ['date' => $validated['date']])
            ->with('success', 'Isu Dorcat baru berhasil ditambahkan.');
    }

    public function update(Request $request, Dorcat $dorcat): RedirectResponse
    {
        $validated = $request->validate([
            'issue_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'percentage' => ['required', 'integer', 'between:0,100'],
            'status' => ['required', 'in:open,in_progress,resolved'],
            'priority' => ['required', 'in:normal,high,critical'],
            'pic_name' => ['nullable', 'string', 'max:100'],
        ]);

        $dorcat->update(array_merge($validated, ['updated_by' => Auth::id()]));

        $dateStr = \Carbon\Carbon::parse($dorcat->date)->toDateString();
        return redirect()->route('admin.dorcat.index', ['date' => $dateStr])
            ->with('success', 'Data Dorcat berhasil diperbarui.');
    }

    public function destroy(Dorcat $dorcat): RedirectResponse
    {
        $dateStr = \Carbon\Carbon::parse($dorcat->date)->toDateString();
        $dorcat->delete();

        return redirect()->route('admin.dorcat.index', ['date' => $dateStr])
            ->with('success', 'Isu Dorcat berhasil dihapus.');
    }
}
