<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:16 PM
 */

namespace App\UserDirectory\Services\User;

use App\Events\UserCreateOrUpdate;
use App\UserDirectory\Exceptions\Validator\ElasticException;
use App\UserDirectory\Exceptions\Validator\UserException;
use App\UserDirectory\Models\User;
use App\UserDirectory\Models\UserFriend;
use App\UserDirectory\Services\Elastic\Elastic;
use App\UserDirectory\Services\IService;
use App\UserDirectory\Services\IUser;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Facades\Auth;

class UserService implements IService, IUser
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

    /**
     * get user information by id
     * @param $userId
     * @return mixed
     */
    public function getUserById($userId)
    {
        //TODO : put information into memcache
        //TODO: check for user in memcache . if it is not in memcache load it from database
        $user = User::find($userId);

        if (null === $user) {
            return false;
        }

        return json_decode($user);
    }


    /**
     * @return int|null
     */
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

            // Update Elastic search
            // broadcast event
            $model = (new SearchableModelFactory())->getModel(self::ELASTIC_MODEL);
            event(new UserCreateOrUpdate($model, $result->id));

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
            // broadcast event
            $model = (new SearchableModelFactory())->getModel(self::ELASTIC_MODEL);
            event(new UserCreateOrUpdate($model, Auth::id()));

            return $result;

        } catch (UserException $exception) {

            throw  $exception;

        }
    }

    /**
     * search against all fields in elastic
     * @param $term
     * @return mixed
     * @throws ElasticException
     * @internal param $model
     */
    public function search($term)
    {
        try {

            $model = $this->getSearchModel();

            // search against all three fields
            $results = $model::search()->multiMatch([
                self::FIELD_NAME,
                self::FIELD_EMAIL,
                self::FIELD_AGE
            ], $term)->getRaw();

            // put data into elastic class
            $elasticObject = new Elastic($results);

            // there is no match
            if ($elasticObject->totalHits() == 0) {
                return false;
            }

            // loop all search results
            $searchResult = [];
            foreach ($elasticObject->hits() as $row) {

                $searchResult[] = [
                    'id' => $row['_source']['id'],
                    'name' => $row['_source']['name'],
                    'email' => $row['_source']['email'],
                    'age' => $row['_source']['age'],
                    'score' => $row['_score']
                ];

            }

            return json_encode($searchResult);

        } catch (ElasticException $exception) {
            throw $exception;
        }


    }

    /**
     * @return mixed|null
     */
    public function getSearchModel()
    {
        return (new SearchableModelFactory())->getModel(self::ELASTIC_MODEL);
    }

    /**
     * @param $friendId
     * @return $result
     */
    public function AddToFriendList($friendId)
    {

        try {

            $result = UserFriend::create([
                self::FIELD_USER_ID => $this->getUserId(),
                self::FIELD_FRIEND_ID => $friendId
            ]);

            return $result;

        } catch (MassAssignmentException $exception) {
            throw $exception;
        }

    }


    /**
     * User cannot add himself/herself to his/her friend list !
     * @param $friendId
     * @return bool
     */
    public function checkFriendShip($friendId)
    {
        if ($this->getUserId() == $friendId)
            return false;

        // is there this relation exist before
        $relationExist = UserFriend::where('user_id', $this->getUserId())
            ->where('friend_id', $friendId)->first();
        if($relationExist)
            return false;

        return true;
    }



}