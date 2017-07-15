<?php

namespace App\Http\Controllers\Profile;

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
     * view profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        return view('dashboard.profile.view');
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

        $update = null ;
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






}
