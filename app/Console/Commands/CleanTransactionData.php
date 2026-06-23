<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanTransactionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus semua riwayat transaksi dan kas, tapi jaga akun login (users) tetap ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pembersihan data...');

        // Disable foreign key constraints sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Truncate tabel-tabel terkait transaksi dan kas
            $tables = [
                'transaction_items',
                'transactions',
                'expenses',  // Tabel belanjaan yang disimpan per closing
                'cash_expenses',
                'cash_closings',
                'cash_openings',
                'cash_sessions',
                'open_bills',
            ];

            foreach ($tables as $table) {
                DB::table($table)->truncate();
                $this->info("✓ Tabel '{$table}' sudah dikosongkan");
            }

            // Enable foreign key constraints kembali
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info('');
            $this->line('<fg=green;options=bold>✓ Pembersihan data selesai!</>');
            $this->line('<fg=green>Akun login (users) tetap ada dan siap digunakan</>');

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
