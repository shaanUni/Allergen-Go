<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;


Schedule::command('app:failed-payment-email')->daily()->at('2:00');
Schedule::command('app:check-logs')->weekly();
