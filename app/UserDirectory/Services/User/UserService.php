<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:16 PM
 */

namespace App\UserDirectory\Services\User;

use App\Events\UserCreateOrUpdate;
use App\UserDirectory\Exceptions\Validator\UserException;
use App\UserDirectory\Models\User;
use App\UserDirectory\Services\IService;
use Illuminate\Support\Facades\Auth;

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
     * get all information about user
     * @param bool $loadFromCache decide to load data from memory or fetch it from Database
     * @return mixed
     */
    public function getCurrentUser($loadFromCache = true)
    {
        if (!$loadFromCache) {
            $user = User::find(Auth::user()->id);
            return json_decode($user);
        }

        return Auth::user();

    }

    public function getUserId()
    {
        return Auth::id();
    }


    /**
     * create new user in system
     * @param $data
     * @return mixed
     * @throws UserException
     */
    public function createUser($data = array())
    {
        if (empty($data)) {

            throw new UserException("Cannot create user. data is null");
            // log data can happen here
        }

        try {

            $result = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'age' => $data['age'],
                'password' => bcrypt($data['password']),
            ]);

            // Update Elastic search with new entry
            $result->document()->save();

            // broadcast event
            // event(new UserCreateOrUpdate($result));

            return $result;

        } catch (UserException $exception) {

            throw $exception;
        }


    }

    /**
     * update user details
     * @param array $data
     * @param bool $updatePassword
     * @return mixed
     * @throws UserException
     */
    public function updateUser($data = array(), $updatePassword = false)
    {
        if (empty($data)) {

            throw new UserException('Cannot update user. data is null');
            // log data can happen here
        }

        if ($updatePassword) {

            $data['password'] = bcrypt($data['password']);

        }

        try {

            $result = User::where('id', Auth::id())->update($data);

            // Update Elastic search
            User::where('id', Auth::id())->first()->document()->save();

            // broadcast event if need
            // event(new UserCreateOrUpdate($result));

            return $result;

        } catch (UserException $exception) {

            throw  $exception;

        }
    }
}