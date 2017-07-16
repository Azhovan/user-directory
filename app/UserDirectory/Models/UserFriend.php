<?php

namespace App\UserDirectory\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'friend_id'
    ];


}
