<?php

namespace App\Console\Commands;

use App\Http\Controllers\DashboardController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class ExportDocsCommand extends Command
{
    protected $signature = 'export:docs {--days=7 : Jumlah hari terakhir yang diekspor}';
    protected $description = 'Ekspor data dashboard monitoring ke docs/data/monitoring.json untuk GitHub Pages';

    public function handle(DashboardController $dashboardController): int
    {
        $this->info('Memulai ekspor data ke GitHub Pages (docs/data/monitoring.json)...');

        $days = (int) $this->option('days');
        $output = [];

        // Muat file yang sudah ada jika ada agar data historis tidak hilang
        $jsonPath = base_path('docs/data/monitoring.json');
        if (file_exists($jsonPath)) {
            $existing = json_decode(file_get_contents($jsonPath), true);
            if (is_array($existing)) {
                $output = $existing;
            }
        }

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $this->line("Mengambil data tanggal: {$date}");

            $req = Request::create('/api/dashboard-data', 'GET', ['date' => $date]);
            $res = $dashboardController->apiData($req);
            $data = json_decode($res->getContent(), true);

            $output[$date] = $data;
        }

        file_put_contents($jsonPath, json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info("Berhasil! Data {$days} hari tersimpan di docs/data/monitoring.json");

        return self::SUCCESS;
    }
}
