<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:17 PM
 */

namespace App\UserDirectory\Services;


interface ISearch
{
    /**
     * get the model which we are going to search in Elastic
     * @param $model
     * @return mixed
     */
    public function getModel($model);

}