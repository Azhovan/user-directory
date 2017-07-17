<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/16/17
 * Time: 12:00 PM
 */

namespace App\UserDirectory\Config;


interface Constants
{

    const CURRENT_PROFILE = 'current';

    const OTHER_PROFILE = 'other';

    const NO_MATCH='There is no match for this user in our database' ;

    const RESPONSE = 'response';

    const STATUS = 'status';

    const SUCCESS = 'success';

    const ERROR = 'error';

    const EMPTY_FRIEND_LIST ='You have not any friend in your list yet, be active a little bit !';

    const MEMCACHE_TIME_TO_LIVE = 10;


}