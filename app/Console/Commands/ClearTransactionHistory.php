<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTransactionHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan semua data transaksi, cash opening, cash closing, dan expenses tanpa menghapus data users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Apakah Anda yakin ingin menghapus semua data transaksi? Data users akan tetap ada. [y/N]')) {
            try {
                DB::transaction(function () {
                    // Nonaktifkan foreign key constraint sementara
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');

                    // Truncate tables
                    DB::table('transaction_items')->truncate();
                    DB::table('transactions')->truncate();
                    DB::table('expenses')->truncate();
                    DB::table('cash_closings')->truncate();
                    DB::table('cash_openings')->truncate();

                    // Aktifkan kembali foreign key constraint
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');

                    $this->info('✓ Data transaksi berhasil dibersihkan.');
                    $this->info('✓ Data users tetap tersimpan.');
                });
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
            }
        } else {
            $this->info('Pembersihan dibatalkan.');
        }
    }
}
