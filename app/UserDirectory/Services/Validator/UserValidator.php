<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 12:31 PM
 */

namespace App\UserDirectory\Services\Validator;

use App\UserDirectory\Config\ExceptionsConstant;
use App\UserDirectory\Exceptions\Validator\UserException;
use Illuminate\Support\Facades\Validator;

class UserValidator implements IValidate
{

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $roles;

    public function __construct($_parameters, $_roles)
    {
        $this->setParameters($_parameters);
        $this->setRoles($_roles);
    }


    public function validator()
    {
        if (empty($this->getParameters()) || empty($this->getRoles()))
            throw new UserException(ExceptionsConstant::INVALID_PARAMETERS);

        return Validator::make($this->getParameters(), $this->getRoles());
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param array $roles
     * @return mixed
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }
}