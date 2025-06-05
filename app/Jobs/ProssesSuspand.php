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

    public function __construct()
    {
    }

    public function handle(): void
    {
        $data['now'] = date('Y-m-d', strtotime(Carbon::now()));
        $unp = Invoice::where('inv_status', 'UNPAID')->whereDate('inv_tgl_jatuh_tempo', '<=', $data['now'])->get();
        foreach ($unp as $d) {
            Invoice::where('corporate_id',$d->corporate_id)->where('inv_id', $d->inv_id)->update([
                'inv_status' => 'SUSPEND',
            ]);
            Registrasi::where('corporate_id',$d->corporate_id)->where('reg_idpel', $d->inv_idpel)->update([
                'reg_status' => 'SUSPEND',
            ]);
        }
    }
}
