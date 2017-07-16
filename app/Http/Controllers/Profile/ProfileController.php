<?php

namespace App\Http\Controllers\Profile;

use App\UserDirectory\Config\Constants;
use App\UserDirectory\Services\Response\Response;
use App\UserDirectory\Services\User\UserService;
use App\UserDirectory\Services\Validator\UserValidator;
use App\UserDirectory\Services\Validator\ValidateFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    /**
     * @var bool
     */
    protected $shouldUpdatePassword = true;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var array
     */
    private $profile;

    /**
     * @var string
     */
    private $profileType;

    /**
     * view profile current user and the others
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request, $id = null)
    {
        $profile = UserService::getInstance()->getCurrentUser();
        $this->profileType = Constants::CURRENT_PROFILE;

        if (isset($id) && !empty($id)) {
            $profile = UserService::getInstance()->getUserById($id);
            $this->profileType = Constants::OTHER_PROFILE;
        }

        return view('dashboard.profile.view', [
            'profile' => $profile,
            'profileType' => $this->profileType
        ]);
    }

    /**
     * edit profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('dashboard.profile.edit')->with('user',
            UserService::getInstance()->getCurrentUser()
        );
    }

    /**
     * update user profile setting
     * @param Request $request
     * @return $this
     */
    public function update(Request $request)
    {

        $userProfile = $request->all();

        $this->roles = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email,' . UserService::getInstance()->getUserId(),
            'age' => 'required|integer|min:1|max:100',
        ];

        $this->profile = [
            'name' => $userProfile['name'],
            'email' => $userProfile['email'],
            'age' => $userProfile['age'],
        ];


        if (empty($userProfile['password'])) {
            $this->shouldUpdatePassword = false;
        }

        if ($this->shouldUpdatePassword) {
            $this->roles['password'] = 'required|string|min:6';
            $this->profile['password'] = $userProfile['password'];
        }

        $validator = ValidateFactory::validate(
            new UserValidator($this->profile, $this->roles)
        );

        $update = null;
        if (!$validator->fails()) {
            $update = UserService::getInstance()->updateUser($this->profile, $this->shouldUpdatePassword);
        }

        // load view with data from database, because the user's data was changed
        return view('dashboard.profile.edit')->with([
                'user' => UserService::getInstance()->getCurrentUser(false),
                'update' => $update
            ]
        )->withErrors($validator->messages());
    }


    /**
     * add friend to user friend list
     * @param Request $request
     * @return mixed
     * @internal param $id
     */
    public function addFriend(Request $request)
    {
        $data = $request->all();

        // error
        if (empty($data['id']) || !isset($data['id'])) {
            (new Response(Constants::ERROR))->handleResponse()->getResponseResult();
        }

        // check feasibility
        if (
            UserService::getInstance()->checkFriendShip($data['id']) &&
            UserService::getInstance()->getUserById($data['id'])
        ) {

            $result = UserService::getInstance()->AddToFriendList($data['id']);

            return (new Response($result))->getMapping()
                ->handleResponse()->getResponseResult();
        }

        // error
        return (new Response(Constants::ERROR))->handleResponse()->getResponseResult();


    }

    /**
     * show friend list page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function friends()
    {
        $friends = UserService::getInstance()->getCurrentUserFriends();

        return view('dashboard.profile.friendsList', [
            'friends' => $friends
        ]);

    }


}
