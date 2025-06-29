<?php

namespace App\Console;

use App\Jobs\invoiceJob;
use App\Jobs\ProsesBayarPengurus;
use App\Jobs\ProssesIsolir;
use App\Jobs\ProssesSuspand;
use App\Jobs\ProssesTagihan;
use App\Jobs\SendMessage;
use App\Jobs\WaJob;
use App\Jobs\WhatsappInvoiceJob;
use App\Jobs\NotifTelatPembayaran;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->job(new ProssesSuspand)->dailyAt('05:50');
        $schedule->job(new ProssesTagihan)->dailyAt('08:10');
        // $schedule->job(new NotifTelatPembayaran)->dailyAt('09:00');
        $schedule->job(new ProsesBayarPengurus)->dailyAt('07:20');
        $schedule->job(new SendMessage)->everyTwentySeconds();
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
