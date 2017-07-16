<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:17 PM
 */

namespace App\UserDirectory\Services;

/**
 * class type
 * Interface IUser
 * @package App\UserDirectory\Services
 */
interface IUser
{

    const ELASTIC_MODEL = 'User';
    const FIELD_ID= 'id';
    const FIELD_NAME = 'name';
    const FIELD_EMAIL = 'email';
    const FIELD_AGE = 'age';
    const FIELD_USER_ID ='user_id';
    const FIELD_FRIEND_ID ='friend_id';

}