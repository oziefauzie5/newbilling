<?php

namespace App\Jobs;

use App\Models\PSB\Registrasi;
use App\Models\Transaksi\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProssesSuspand implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $unp = Invoice::where('inv_status', 'UNPAID')->whereDate('inv_tgl_jatuh_tempo', '<=', $data['now'])->get();
        foreach ($unp as $d) {
            Invoice::where('inv_id', $d->inv_id)->update([
                'inv_status' => 'SUSPAND',
            ]);
            Registrasi::where('reg_idpel', $d->inv_idpel)->update([
                'reg_status' => 'SUSPAND',
            ]);
        }
    }
}
