<?php

namespace App\UserDirectory\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Sleimanx2\Plastic\Searchable;

class User extends Authenticatable
{
    use Notifiable;

    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'age'
    ];

    /**
     * which fields can be search in elastic search
     * @var array
     */
    public $searchable = ['id', 'name', 'email', 'age'];

    /**
     * decide to automatically sync model to Elastic search or not
     * default to false
     * @var bool
     */
    public $syncDocument = false;


}
