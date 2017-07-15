<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/15/17
 * Time: 4:43 PM
 */

namespace App\UserDirectory\Services\User;


use App\UserDirectory\Exceptions\Validator\ElasticException;
use App\UserDirectory\Services\ISearch;

class SearchableModelFactory implements ISearch
{

    /**
     * @var array
     */
    public $allowedModels = array(
        'User' => 'App\UserDirectory\Models\User'
    );


    /**
     * @param $key
     * @return mixed|null
     * @throws ElasticException
     */
    private function getMapping($key)
    {
        if (array_key_exists($key, $this->allowedModels)) {

            return $this->allowedModels[$key];
        }
        throw new ElasticException('There is no mapping for elasticSearch mapping:' . $key);
    }

    /**
     * find the model
     * @param $model
     * @return mixed|null
     * @throws ElasticException
     */
    public function getModel($model)
    {
        if (null == $model) {
            throw new ElasticException('Model not found : ' . $model);
        }

        return $this->getMapping($model);
    }


}