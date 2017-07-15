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
        $model = $event->model;

        $model::where('id', $event->id)->first()->document()->save();
    }

    /**
     * @param UserCreateOrUpdate $event
     * @param $exception
     */
    public function failed(UserCreateOrUpdate $event, $exception)
    {
        // does need to implement
    }
}
