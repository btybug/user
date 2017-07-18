<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Sahakavatar\User\Repository;

use app\helpers\helpers;
use App\Modules\Users\User as UserModel;


use Auth;

/**
 * @property dbhelper dhelp
 * @property helpers helpers
 */
class User
{
    /**
     * Page constructor.
     */
    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function logeduser()
    {
        $user = Auth::user();
        // $info = $this->helpers->common_settings();
        //$info['user'] = $user;

        return $user;
    }

    /**
     * @param $request
     */
    public function updateFront($request)
    {
        $req = $request->all();

        if (!$request->id) {
            $user = Auth::user();
            $id = $user->id;
        } else {
            $id = $request->id;
        }

        $user = UserModel::find($id);
        $user->email = $req['email'];
        $user->username = $req['username'];
        if ($req['password'] != '') {
            $user->password = bcrypt($user->password);
        }
        $user->save();

        if (!$request->id) {
            return "/my_account";
        } else {
            return "/users";
        }
    }

}