<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 12:51 PM
 */

namespace App\UserDirectory\Services\Validator;


class ValidateFactory implements IFactory
{

    public static function validate($builder)
    {
        return $builder->validator();
    }
}