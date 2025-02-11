<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProfileController;
use Illuminate\Console\Command;

class DowngradeResellers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resellers:downgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turunkan level reseller yang tidak memenuhi syarat pembelian bulanan.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new ProfileController();
        $controller->downgradeReseller();
        $this->info('Downgrade reseller selesai.');
    }
}
