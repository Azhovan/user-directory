<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserDirectory\Config\ExceptionsConstant;
use App\UserDirectory\Exceptions\Validator\ElasticException;
use App\UserDirectory\Models\User;
use App\UserDirectory\Services\User\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\UserDirectory\Services\Validator\UserValidator;
use App\UserDirectory\Services\Validator\ValidateFactory;
use InvalidArgumentException;
use Mockery\Exception;


class RegisterController extends Controller
{


    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return ValidateFactory::validate(
            new UserValidator($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'age' => 'required|integer|min:1|max:100',
            ])
        );

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     * @throws ElasticException
     */
    protected function create(array $data)
    {
        try {
            app('plastic')->connection();
        } catch (InvalidArgumentException $exception) {
            throw new ElasticException(ExceptionsConstant::ELASTIC_IS_NOT_ACCESSIBLE);
        } catch (\Exception $exception) {
            throw new Exception(ExceptionsConstant::ELASTIC_IS_NOT_ACCESSIBLE);
        }

        return UserService::getInstance()->createUser($data);

    }
}
