<?php

namespace App\Jobs;

use App\Models\Opt_in_logs;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteLogsJob implements ShouldQueue
{
    use Queueable;

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
        //
       // Opt_in_logs::where('created_at', '<=', Carbon::now()->subweeks(3))->delete();
       Opt_in_logs::where('created_at', '<=', Carbon::now()->subMinutes(2))->delete();
    }
}
