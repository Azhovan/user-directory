<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/16/17
 * Time: 1:45 PM
 */

namespace App\UserDirectory\Services\Response;


use App\UserDirectory\Config\Constants;
use App\UserDirectory\Config\ExceptionsConstant;
use App\UserDirectory\Services\AbstractResponse;
use Psr\Log\InvalidArgumentException;

class Response extends AbstractResponse
{

    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * @return $this
     */
    public function getMapping()
    {
        $this->data = ($this->data ? Constants::SUCCESS : Constants::ERROR);
        return $this;
    }

    /**
     * get the status of response
     * @return mixed
     */
    public function getStatus()
    {
        return $this->data;
    }

    /**
     * Apply logic for each strategy for handling the response
     * @return mixed
     * @internal param $response
     *
     */
    public function handleResponse()
    {
        if (null == $this->data || empty($this->data)) {
            throw new InvalidArgumentException(ExceptionsConstant::INVALID_PARAMETERS);
        }

        $this->responseResult = json_encode([
            Constants::RESPONSE => [
                Constants::STATUS => $this->data
            ]
        ]);

        return $this;

    }

    /**
     * no type on return since this can be used by other strategies
     * @return mixed
     */
    public function getResponseResult()
    {
        return $this->responseResult;
    }
}