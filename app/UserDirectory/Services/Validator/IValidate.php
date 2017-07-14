<?php
/**
 * Created by PhpStorm.
 * User: jabar asadi <asadi.jabar@gmail.com>
 * Date: 7/14/17
 * Time: 12:27 PM
 */

namespace App\UserDirectory\Services\Validator;

use Illuminate\Support\Facades\Validator;


interface IValidate
{

    /**
     * validate input parameters with given roles
     * @return bool
     * @internal param array $parameters
     * @internal param array $roles
     * @throws App\UserDirectory\Exceptions\Validator\UserException
     */
    public function validator();

    /**
     * @return array
     */
    public function getRoles();

    /**
     *
     * @return array
     */
    public function getParameters();

    /**
     * @param array $parameters
     * @return mixed
     */
    public function setParameters(array $parameters);

    /**
     * @param array $roles
     * @return mixed
     */
    public function setRoles(array $roles);

}