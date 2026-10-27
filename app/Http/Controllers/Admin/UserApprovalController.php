<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function index(): View
    {
        $pendingUsers = User::where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedUsers = User::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', compact('pendingUsers', 'approvedUsers'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', "Akun pengguna {$user->name} ({$user->email}) telah berhasil disetujui.");
    }

    public function reject(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menolak/menghapus akun Anda sendiri.']);
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Pendaftaran pengguna {$userName} telah ditolak dan dihapus dari sistem.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:super_admin,operator'],
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== 'super_admin') {
            return back()->withErrors(['error' => 'Anda tidak dapat menurunkan role akun Anda sendiri.']);
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role pengguna {$user->name} diperbarui menjadi {$validated['role']}.");
    }
}
