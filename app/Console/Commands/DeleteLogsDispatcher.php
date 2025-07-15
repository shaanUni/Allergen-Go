<?php

namespace App\Console\Commands;

use App\Jobs\DeleteLogsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteLogsDispatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will find GDPR logs in the DB, that have been added over 3 weeks ago - then delete them.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Log::info('logs command has been triggered by cron');
        DeleteLogsJob::dispatch();
    }
}
