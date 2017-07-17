<?php

namespace App\Listeners;

use App\Events\UserCreateOrUpdate;
use App\UserDirectory\Config\Constants;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

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

        // save user in elastic search
        $model::where('id', $event->id)->first()->document()->save();

        // save user data in Memcache
        // the Memcache data is always updated (at Create | Update user functions )
        Cache::put($event->id, serialize($model::where('id', $event->id)->first()), Constants::MEMCACHE_TIME_TO_LIVE) ;
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
