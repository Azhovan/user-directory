<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserCreateOrUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var string
     * the model which event occur on that
     */
    public $model;

    /**
     * identifier of model in database
     * @var integer
     */
    public $id;

    /**
     * Create a new event instance.
     * @param $model
     * @param $id
     * @internal param $_userProfile
     */
    public function __construct($model, $id)
    {
        $this->model = $model;
        $this->id = $id;
    }

}
