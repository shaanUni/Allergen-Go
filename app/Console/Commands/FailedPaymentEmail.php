<?php

namespace App\Console\Commands;

use App\Jobs\FailedPaymentEmailJob;
use Illuminate\Console\Command;

class FailedPaymentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:failed-payment-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will find admins who have failed payments, and send an email to them once some time has elapsed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        //FailedPaymentEmailJob::dispatch();
    }
}
