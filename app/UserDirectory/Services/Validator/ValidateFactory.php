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

    /**
     * validator factory class
     * @param $builder IValidate
     * @return mixed
     */
    public static function validate($builder)
    {
        return $builder->validator();
    }
}