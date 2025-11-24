<?php

namespace App\Console\Commands;

use App\Jobs\DeleteLogsJob;
use App\Models\DishShare;
use App\Notifications\DishShareNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;

//Send weekly emails to those who do not accept or delcine the invitation email
class DishShareRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-dish-share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will find the receivers of dish share requsts and send them an email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //Get a list of dish share requests where it has not been declined yet. 
        $dishShareReceivers = DishShare::where('status', false)->where('declined', false)->get();
        
        foreach($dishShareReceivers as $receiver){
            $admin = Admin::find($receiver->child_admin_id);
            $email = Admin::find($receiver->parent_admin_id)->email;
            $uuid = $receiver->uuid;
            $admin->notify(new DishShareNotification($email, $uuid));
        }
    }
}