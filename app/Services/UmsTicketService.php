<?php

namespace App\Services;

use App\Models\UmsTicketSetting;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UmsTicketService
{
    /**
     * Fetch and synchronize ticket counts directly from umsticket.artyc.id.
     */
    public function syncWorkOrders(?string $date = null): array
    {
        $targetDate = $date ?? Carbon::today()->toDateString();
        $setting = UmsTicketSetting::first();
        $endpoint = $setting?->endpoint_url ?? 'https://umsticket.artyc.id';

        try {
            // Fetch live HTML from umsticket.artyc.id
            $response = Http::withoutVerifying()
                ->timeout(12)
                ->withHeaders([
                    'User-Agent' => 'GSMTBU-Dashboard/1.0',
                    'Accept' => 'text/html,application/xhtml+xml,application/json',
                ])
                ->get($endpoint);

            if (! $response->successful()) {
                throw new Exception("HTTP status error {$response->status()} dari {$endpoint}");
            }

            $html = $response->body();
            $tickets = [];

            // Extract embedded publicReportsMap JavaScript object
            if (preg_match('/const\s+publicReportsMap\s*=\s*(\{.*?\});/s', $html, $matches)) {
                $tickets = json_decode($matches[1], true) ?? [];
            } elseif (preg_match('/reports\s*:\s*(\[.*?\])\s*,\s*dateOptions/s', $html, $matches)) {
                $tickets = json_decode($matches[1], true) ?? [];
            }

            if (empty($tickets)) {
                throw new Exception("Gagal membaca struktur data tiket dari halaman {$endpoint}");
            }

            // Aggregate tickets into Department (MEP vs Sipil), Category (Minor, Mayor, Project, Unidentified), and Status
            $counts = [
                'mep' => [
                    'minor' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                    'mayor' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                    'project' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                    'unidentified' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                ],
                'sipil' => [
                    'minor' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                    'mayor' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                    'project' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                    'unidentified' => ['waiting_schedule' => 0, 'in_progress' => 0, 'completed' => 0],
                ],
            ];

            $sipilTicketCount = 0;

            foreach ($tickets as $t) {
                $catType = strtolower($t['category_type'] ?? '');
                $subCat = strtolower($t['sub_category'] ?? '');
                $desc = strtolower($t['issue_description'] ?? '');
                $status = strtolower($t['status'] ?? '');
                $rawJobType = strtolower(trim($t['job_type'] ?? ''));

                // 1. Department mapping (Carpenter or Sipil -> Building Const / Sipil; MEP or Instrument -> MEP)
                if (str_contains($catType, 'carpenter') || str_contains($catType, 'sipil') || str_contains($subCat, 'carpenter') || str_contains($subCat, 'bangunan') || str_contains($subCat, 'atap')) {
                    $dept = 'sipil';
                    $sipilTicketCount++;
                } else {
                    $dept = 'mep';
                }

                // 2. Category mapping (Minor, Mayor, Project, Belum Diidentifikasi) from job_type field
                if (str_contains($rawJobType, 'project') || str_contains($rawJobType, 'proyek') || str_contains($desc, 'project') || str_contains($subCat, 'project')) {
                    $type = 'project';
                } elseif (str_contains($rawJobType, 'mayor') || str_contains($rawJobType, 'major')) {
                    $type = 'mayor';
                } elseif (str_contains($rawJobType, 'minor')) {
                    $type = 'minor';
                } else {
                    // '-' or empty or not yet determined by validator
                    $type = 'unidentified';
                }

                // 3. Status mapping
                if (in_array($status, ['pending_validation', 'waiting_schedule', 'waiting'])) {
                    $st = 'waiting_schedule';
                } elseif (in_array($status, ['scheduled', 'on_progress', 'started', 'in_progress'])) {
                    $st = 'in_progress';
                } elseif (in_array($status, ['completed', 'resolved', 'closed'])) {
                    $st = 'completed';
                } else {
                    $st = 'waiting_schedule';
                }

                $counts[$dept][$type][$st]++;
            }

            $allCategories = ['minor', 'mayor', 'project', 'unidentified'];

            // Save MEP & Instrument to work_orders
            foreach ($allCategories as $category) {
                $existing = WorkOrder::whereDate('date', $targetDate)
                    ->where('department', 'mep')
                    ->where('category', $category)
                    ->first();

                $payload = [
                    'waiting_schedule' => $counts['mep'][$category]['waiting_schedule'],
                    'in_progress' => $counts['mep'][$category]['in_progress'],
                    'completed' => $counts['mep'][$category]['completed'],
                    'source' => 'umsticket',
                    'last_synced_at' => now(),
                ];

                if ($existing) {
                    $existing->update($payload);
                } else {
                    WorkOrder::create(array_merge([
                        'date' => $targetDate,
                        'department' => 'mep',
                        'category' => $category,
                    ], $payload));
                }
            }

            // Save Building Const (Carpenter / Sipil) to work_orders
            foreach ($allCategories as $category) {
                $existingSipil = WorkOrder::whereDate('date', $targetDate)
                    ->where('department', 'sipil')
                    ->where('category', $category)
                    ->first();

                $payload = [
                    'waiting_schedule' => $counts['sipil'][$category]['waiting_schedule'],
                    'in_progress' => $counts['sipil'][$category]['in_progress'],
                    'completed' => $counts['sipil'][$category]['completed'],
                    'source' => 'umsticket',
                    'last_synced_at' => now(),
                ];

                if ($existingSipil) {
                    $existingSipil->update($payload);
                } else {
                    WorkOrder::create(array_merge([
                        'date' => $targetDate,
                        'department' => 'sipil',
                        'category' => $category,
                    ], $payload));
                }
            }

            // Update setting status
            $msg = 'Berhasil sinkronisasi langsung ' . count($tickets) . ' tiket dari ' . $endpoint;
            if ($setting) {
                $setting->update([
                    'last_synced_at' => now(),
                    'last_sync_status' => 'success',
                    'last_sync_message' => $msg,
                ]);
            }

            return [
                'success' => true,
                'message' => $msg,
                'total_tickets' => count($tickets),
            ];
        } catch (Exception $e) {
            Log::warning('Sinkronisasi umsticket.artyc.id error: ' . $e->getMessage());

            if ($setting) {
                $setting->update([
                    'last_synced_at' => now(),
                    'last_sync_status' => 'failed',
                    'last_sync_message' => 'Gagal koneksi: ' . $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Gagal sinkronisasi: ' . $e->getMessage(),
            ];
        }
    }
}
