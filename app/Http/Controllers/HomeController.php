<?php

namespace App\Http\Controllers;

use App\UserDirectory\Config\Messages;
use App\UserDirectory\Services\Response\Response;
use App\UserDirectory\Services\User\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

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
        return view('dashboard.profile.edit');
    }

    public function search(Request $request)
    {
        $param = $request->all();

        if (!isset($param['term']) || empty($param['term'])) {
            return Messages::ERROR_REQUEST;
        }

        $result = UserService::getInstance()->search($param['term']);
        if (false === $result)
            return (new Response($result))->getMapping()
                ->handleResponse()->getResponseResult();

        return ($result);

    }
}
