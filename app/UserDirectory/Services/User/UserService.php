<?php
/**
 * Created by PhpStorm.
 * User: azhi
 * Date: 7/14/17
 * Time: 1:16 PM
 */

namespace App\UserDirectory\Services\User;

use App\Events\UserCreateOrUpdate;
use App\UserDirectory\Config\Constants;
use App\UserDirectory\Exceptions\Validator\ElasticException;
use App\UserDirectory\Exceptions\Validator\UserException;
use App\UserDirectory\Models\User;
use App\UserDirectory\Models\UserFriend;
use App\UserDirectory\Services\Elastic\Elastic;
use App\UserDirectory\Services\IService;
use App\UserDirectory\Services\IUser;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use UnexpectedValueException;

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

            // put updated information in Cache
            Cache::put($this->getUserId(), serialize($user), Constants::MEMCACHE_TIME_TO_LIVE);

            return json_decode($user);
        }

        // load data from cache
        // if it exist in Memcache
        if (Cache::has($this->getUserId())) {
            return unserialize(Cache::get($this->getUserId()));
        }

        // if does not exist
        $user = Auth::user();
        Cache::put($this->getUserId(), serialize($user), Constants::MEMCACHE_TIME_TO_LIVE);

        return $user;

    }

    /**
     * get user information by id
     * @param $userId
     * @return mixed
     */
    public function getUserById($userId)
    {

        // if user exist in cache
        if (Cache::has($userId)) {
            return unserialize(Cache::get($userId));
        }

        $user = User::find($userId);

        if (null === $user) {
            return false;
        }

        // user exist so :: we can update the cache and return it
        Cache::put($userId, serialize($userId), Constants::MEMCACHE_TIME_TO_LIVE);

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
                self::FIELD_NAME => $data[self::FIELD_NAME],
                self::FIELD_EMAIL => $data[self::FIELD_EMAIL],
                self::FIELD_AGE => $data[self::FIELD_AGE],
                self::FIELD_PASSWD => bcrypt($data[self::FIELD_PASSWD]),
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

            $data[self::FIELD_PASSWD] = bcrypt($data[self::FIELD_PASSWD]);

        }

        try {

            $result = User::where(self::FIELD_ID, Auth::id())->update($data);

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
                    self::FIELD_ID => $row[self::ELASTIC_SOURCE][self::FIELD_ID],
                    self::FIELD_NAME => $row[self::ELASTIC_SOURCE][self::FIELD_NAME],
                    self::FIELD_EMAIL => $row[self::ELASTIC_SOURCE][self::FIELD_EMAIL],
                    self::FIELD_AGE => $row[self::ELASTIC_SOURCE][self::FIELD_AGE],
                    self::FIELD_SCORE => $row[self::ELASTIC_SCORE]
                ];

            }

            return json_encode($searchResult);

        } catch (ElasticException $exception) {
            throw $exception;
        } catch (UnexpectedValueException $exception) {
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
        $relationExist = UserFriend::where(self::FIELD_USER_ID, $this->getUserId())
            ->where(self::FIELD_FRIEND_ID, $friendId)->first();
        if ($relationExist)
            return false;

        return true;
    }

    /**
     * find All friends related to current user
     * @return bool|string
     */
    public function getCurrentUserFriends()
    {
        $result = User::whereIn(self::FIELD_ID, function ($query) {
            $query->select('friend_id as id')
                ->from('users')
                ->join('user_friends', 'users.id', '=', 'user_friends.user_id')
                ->where('users.id', $this->getUserId());
        })->orderBy('created_at', 'DESC')->get();

        if (null == $result)
            return false;

        return $result;
    }


}