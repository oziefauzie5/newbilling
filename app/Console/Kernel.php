<?php

namespace App\Console;

use App\Jobs\invoiceJob;
use App\Jobs\ProssesIsolir;
use App\Jobs\ProssesSuspand;
use App\Jobs\WaJob;
use App\Jobs\WhatsappInvoiceJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->job(new WhatsappInvoiceJob)->everyTwentySeconds();
        // $schedule->job(new WaJob)->everyTwentySeconds();
        $schedule->job(new ProssesSuspand)->dailyAt('00:20');
        $schedule->job(new ProssesIsolir)->everySecond();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
