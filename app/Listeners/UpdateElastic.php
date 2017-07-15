<?php

namespace App\Listeners;

use App\Events\UserCreateOrUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateElastic implements ShouldQueue
{

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserCreateOrUpdate $event
     * @return bool
     */
    public function handle(UserCreateOrUpdate $event)
    {
        //print_r($event->userProfile['id']);
        // return false;
    }

    public function failed(UserCreateOrUpdate $event, $exception)
    {
        //TODO :
    }
}
