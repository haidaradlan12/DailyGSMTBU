<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CostController;
use App\Http\Controllers\Admin\DorcatController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\WorkOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public Executive Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/dashboard-data', [DashboardController::class, 'apiData'])->name('api.dashboard.data');

// Authentication & Registration
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Panel (Requires Authenticated & Approved User)
Route::prefix('admin')->middleware(['auth', 'approved'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Bagian 1: Costs
    Route::get('/costs', [CostController::class, 'index'])->name('costs.index');
    Route::post('/costs', [CostController::class, 'store'])->name('costs.store');

    // Bagian 2: Dorcat
    Route::get('/dorcat', [DorcatController::class, 'index'])->name('dorcat.index');
    Route::post('/dorcat', [DorcatController::class, 'store'])->name('dorcat.store');
    Route::put('/dorcat/{dorcat}', [DorcatController::class, 'update'])->name('dorcat.update');
    Route::delete('/dorcat/{dorcat}', [DorcatController::class, 'destroy'])->name('dorcat.destroy');

    // Bagian 3: Transport
    Route::get('/transport', [TransportController::class, 'index'])->name('transport.index');
    Route::post('/transport', [TransportController::class, 'store'])->name('transport.store');

    // Bagian 4 & 5: MEP Instrument & Sipil
    Route::get('/work-orders', [WorkOrderController::class, 'index'])->name('work_orders.index');
    Route::post('/work-orders', [WorkOrderController::class, 'update'])->name('work_orders.update');
    Route::post('/work-orders/sync', [WorkOrderController::class, 'sync'])->name('work_orders.sync');
    Route::post('/work-orders/settings', [WorkOrderController::class, 'updateSettings'])->name('work_orders.settings');

    // Super Admin: User Approval & Role Management
    Route::middleware(['super_admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserApprovalController::class, 'index'])->name('index');
        Route::post('/{user}/approve', [UserApprovalController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [UserApprovalController::class, 'reject'])->name('reject');
        Route::post('/{user}/role', [UserApprovalController::class, 'updateRole'])->name('role');
    });
});
