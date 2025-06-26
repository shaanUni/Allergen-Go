<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

//Schedule::command('app:failed-payment-email')->daily()->at('2:00');
Schedule::command('app:failed-payment-email')->everyFiveMinutes();
