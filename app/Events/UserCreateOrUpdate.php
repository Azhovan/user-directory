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
     * @var Object
     * to hold user profile information
     */
    public $userProfile = [];

    /**
     * Create a new event instance.
     * @param $_userProfile
     */
    public function __construct($_userProfile)
    {
        $this->userProfile = [
            'id' => $_userProfile->id,
            'name' => $_userProfile->name,
            'age' => $_userProfile->age,
            'email' => $_userProfile->email

        ];
    }

}
