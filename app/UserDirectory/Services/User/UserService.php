<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:16 PM
 */

namespace App\UserDirectory\Services\User;

use App\UserDirectory\Exceptions\Validator\UserException;
use App\UserDirectory\Models\User;
use App\UserDirectory\Services\IService;

class UserService implements IService
{

    /**
     * @var IService
     */
    private static $instance;

    /**
     * UserService constructor.
     */
    private function __construct()
    {
    }

    /**
     * get an instance of current service
     * @return mixed
     */
    public static function getInstance()
    {
        if (!isset(self::$instance) || is_null(self::$instance))
            self::$instance = new UserService();

        return self::$instance;
    }


    /**
     * create new user in system
     * @param $data
     * @return mixed
     * @throws UserException
     */
    public function createUser($data)
    {
        if (empty($data))
            throw new UserException("Cannot create user. data is null");

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'age' => $data['age'],
            'password' => bcrypt($data['password']),
        ]);
    }
}