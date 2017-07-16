<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/16/17
 * Time: 1:33 PM
 */

namespace App\UserDirectory\Services;

abstract class AbstractResponse
{


    /**
     * @var mixed
     */
    protected $responseResult;

    /**
     * @var
     */
    protected $data;

    /**
     * Apply logic for each strategy for handling the response
     * @return AbstractResponse
     * @internal param $response
     *
     */
    abstract public function handleResponse();

    /**
     * no type on return since this can be used by other strategies
     * @return mixed
     */
    abstract public function getResponseResult();


    /**
     * mapp the input value to desired status
     * @return AbstractResponse
     */
    abstract public function getMapping();

}