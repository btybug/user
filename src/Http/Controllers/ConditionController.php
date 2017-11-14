<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 6/13/2017
 * Time: 11:58 AM
 */

namespace Sahakavatar\User\Http\Controllers;


use App\Http\Controllers\Controller;

class ConditionController extends Controller
{

    public function getIndex()
    {
        return view('users::condition.index');
    }

}