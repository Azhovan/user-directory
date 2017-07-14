<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 12:52 PM
 */

namespace App\UserDirectory\Services\Validator;


interface IFactory
{

    /**
     * @param $builder
     * @return bool
     */
    public static function validate($builder);

}