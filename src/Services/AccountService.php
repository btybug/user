<?php

/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/19/2017
 * Time: 3:52 PM
 */

namespace Sahakavatar\User\Services;

use Illuminate\Support\Facades\Auth;
use Sahakavatar\Cms\Services\GeneralService;

class AccountService extends GeneralService
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function changePassword(array $data)
    {
        $this->user->email = $data['email'];
        $this->user->password = bcrypt($data['password']);
        $this->user->save();
    }

    public function saveAccount(array $data)
    {
        $data['meta_data'] = isset($data['extra']) ? serialize($data['extra']) : '';

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $this->user->update($data);
    }

}