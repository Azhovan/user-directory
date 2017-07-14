<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:17 PM
 */

namespace App\UserDirectory\Services;


interface IService
{
    /**
     * get an instance of current service
     * @return mixed
     */
    public static function getInstance();

}