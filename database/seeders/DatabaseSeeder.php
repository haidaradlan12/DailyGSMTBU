<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Super Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@gsmtbu.com'],
            [
                'name' => 'Admin Utama GS MTBU',
                'password' => bcrypt('Password123!'),
                'role' => 'super_admin',
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        $today = now()->toDateString();

        // 2. Seed Costs (Tiket, NPE, Office, Mess, Building)
        $costCategories = [
            'tiket' => ['plan' => 50000000, 'actual' => 42500000, 'notes' => 'Tiket perjalanan dinas operasional'],
            'npe' => ['plan' => 120000000, 'actual' => 78000000, 'notes' => 'Non-Personnel Expenses operasional'],
            'office' => ['plan' => 35000000, 'actual' => 31800000, 'notes' => 'ATK dan utilitas kantor'],
            'mess' => ['plan' => 45000000, 'actual' => 36200000, 'notes' => 'Operasional dan konsumsi mess'],
            'building' => ['plan' => 80000000, 'actual' => 38000000, 'notes' => 'Perawatan rutin bangunan fasilitas'],
        ];

        foreach ($costCategories as $cat => $val) {
            $cost = \App\Models\Cost::whereDate('date', $today)->where('category', $cat)->first();
            $payload = [
                'plan_amount' => $val['plan'],
                'actual_amount' => $val['actual'],
                'notes' => $val['notes'],
                'updated_by' => $admin->id,
            ];

            if ($cost) {
                $cost->update($payload);
            } else {
                \App\Models\Cost::create(array_merge(['date' => $today, 'category' => $cat], $payload));
            }
        }

        // 3. Seed Dorcat (Hot Issues and Progress %)
        $dorcatIssues = [
            [
                'issue_title' => 'Overhaul Kompresor Pendingin Gedung Admin MTBU',
                'description' => 'Penggantian sparepart valve dan re-charging refrigerant R410A.',
                'percentage' => 85,
                'status' => 'in_progress',
                'priority' => 'high',
                'pic_name' => 'Budi Santoso',
            ],
            [
                'issue_title' => 'Peningkatan Kualitas Filter Water Treatment Plant Mess GS',
                'description' => 'Backwash dan penggantian media silika serta karbon aktif.',
                'percentage' => 95,
                'status' => 'in_progress',
                'priority' => 'critical',
                'pic_name' => 'Eko Prasetyo',
            ],
            [
                'issue_title' => 'Preventive Maintenance Genset Standby 250 kVA GS',
                'description' => 'Pengecekan accu starter, filter solar, dan load test mingguan.',
                'percentage' => 40,
                'status' => 'open',
                'priority' => 'high',
                'pic_name' => 'Hendra Wijaya',
            ],
            [
                'issue_title' => 'Perbaikan Pintu Otomatis Barrier Gate Utama',
                'description' => 'Penggantian motor servo dan integrasi sensor loop detector.',
                'percentage' => 100,
                'status' => 'resolved',
                'priority' => 'normal',
                'pic_name' => 'Rahmat Hidayat',
            ],
        ];

        foreach ($dorcatIssues as $issue) {
            \App\Models\Dorcat::updateOrCreate(
                ['date' => $today, 'issue_title' => $issue['issue_title']],
                array_merge($issue, ['updated_by' => $admin->id])
            );
        }

        // 4. Seed Transport (PA LV, ATR LV, PA Bus)
        $existingTransport = \App\Models\Transport::whereDate('date', $today)->first();
        $transportPayload = [
            'pa_bus' => 96.00,
            'pa_lv' => 94.50,
            'coverage_pengantaran' => 98.20,
            'fuel_ratio_bus' => 3.65,
            'fuel_ratio_lv' => 7.40,
            'atr_lv' => 88.20,
            'unit_ready_lv' => 38,
            'unit_breakdown_lv' => 2,
            'unit_ready_bus' => 12,
            'unit_breakdown_bus' => 0,
            'notes' => '2 unit LV breakdown sedang menunggu part kopling di workshop.',
            'updated_by' => $admin->id,
        ];
        if ($existingTransport) {
            $existingTransport->update($transportPayload);
        } else {
            \App\Models\Transport::create(array_merge(['date' => $today], $transportPayload));
        }

        // 5. Seed Work Orders (MEP Instrument & Sipil)
        $workOrders = [
            // MEP Instrument
            ['department' => 'mep', 'category' => 'minor', 'waiting_schedule' => 4, 'in_progress' => 8, 'completed' => 26],
            ['department' => 'mep', 'category' => 'mayor', 'waiting_schedule' => 2, 'in_progress' => 5, 'completed' => 14],
            ['department' => 'mep', 'category' => 'project', 'waiting_schedule' => 1, 'in_progress' => 3, 'completed' => 6],
            // Sipil
            ['department' => 'sipil', 'category' => 'minor', 'waiting_schedule' => 3, 'in_progress' => 6, 'completed' => 21],
            ['department' => 'sipil', 'category' => 'mayor', 'waiting_schedule' => 1, 'in_progress' => 4, 'completed' => 9],
            ['department' => 'sipil', 'category' => 'project', 'waiting_schedule' => 2, 'in_progress' => 2, 'completed' => 5],
        ];

        foreach ($workOrders as $wo) {
            $existingWo = \App\Models\WorkOrder::whereDate('date', $today)
                ->where('department', $wo['department'])
                ->where('category', $wo['category'])
                ->first();

            $woPayload = array_merge($wo, [
                'source' => 'umsticket',
                'last_synced_at' => now(),
                'updated_by' => $admin->id,
            ]);

            if ($existingWo) {
                $existingWo->update($woPayload);
            } else {
                \App\Models\WorkOrder::create(array_merge(['date' => $today], $woPayload));
            }
        }

        // 6. Seed Department Notes (Hot Issues)
        \App\Models\DepartmentNote::updateOrCreate(
            ['date' => $today, 'department' => 'mep'],
            [
                'hot_issue' => 'Perbaikan panel ATS genset mess A dalam proses investigasi teknisi.',
                'updated_by' => $admin->id,
            ]
        );

        \App\Models\DepartmentNote::updateOrCreate(
            ['date' => $today, 'department' => 'sipil'],
            [
                'hot_issue' => 'Perbaikan atap shelter bus & pengecoran drainase workshop tahap 2.',
                'updated_by' => $admin->id,
            ]
        );

        // 7. Seed UmsTicketSetting
        \App\Models\UmsTicketSetting::updateOrCreate(
            ['id' => 1],
            [
                'endpoint_url' => 'https://umsticket.artyc.id',
                'auto_sync' => true,
                'last_synced_at' => now(),
                'last_sync_status' => 'success',
                'last_sync_message' => 'Initial sample connection established.',
            ]
        );
    }
}
